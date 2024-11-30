<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use JsonResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with([
            'user',
            'orderDetails.productVariant.product.productImages', // Eager load images for each product
        ])
            ->get();

        $data = $orders->map(function ($order) {
            return [
                "id" => $order->id,
                "customer_name" => $order->user->name,
                "total_order_price" => (float) $order->total_order_price,
                "order_status" => (int) $order->order_status,
                "payment_method" => (int) $order->payment_method,
                "payment_status" => $order->payment_status,
                "created_at" => $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A',
                "order_details" => $order->orderDetails->map(function ($orderDetail) {
                    // Lấy thông tin sản phẩm và hình ảnh
                    $product = $orderDetail->productVariant->product;
                    $images = $product->productImages->map(function ($productImage) {
                        return $productImage->image_url; // Lấy URL hình ảnh
                    });
                    return [
                        "product_name" => $product->name,
                        "quantity" => $orderDetail->quantity,
                        "price" => (float) $orderDetail->productVariant->product->price,
                        "images" => $images, // Thêm danh sách hình ảnh
                    ];
                })
            ];
        });

        return $this->successResponse($data, 'List order');
    }
    public function getOrderById(string $id)
    {
        // Lấy thông tin chi tiết đơn hàng cùng thông tin sản phẩm liên quan
        $orderDetails = OrderDetail::with([
            'order.user', // Lấy thông tin người dùng từ đơn hàng
            'productVariant.product.images' // Lấy thông tin sản phẩm và ảnh sản phẩm
        ])->where('order_id', $id)->get();

        if ($orderDetails->isEmpty()) {
            return $this->notFoundResponse('Order not found');
        }

        // Dữ liệu đơn hàng
        $order = $orderDetails->first()->order;
        $response = [
            'id' => $order->id,
            'created_at' => $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A',
            'customer' => $order->user->name ?? 'N/A',
            'phone_number' => $order->user->phone_number ?? 'N/A',
            'address' => $order->user->address ?? 'N/A',
            'total_order_price' => $order->total_order_price,
            'payment_method' => $order->payment_method == 1 ? "COD" : "VNPay",
            'payment_status' => $order->payment_status == true ? 'Đã thanh toán' : 'Chưa thanh toán',
            'order_status' => $order->order_status,
            'products' => []
        ];

        // Định dạng thông tin các mặt hàng trong đơn hàng
        foreach ($orderDetails as $detail) {
            $product = $detail->productVariant->product;
            $imageUrl = $product->images->first()->image_url ?? 'https://t3.ftcdn.net/jpg/02/48/42/64/360_F_248426448_NVKLywWqArG2ADUxDq6QprtIzsF82dMF.jpg'; // Sử dụng ảnh đầu tiên hoặc ảnh mặc định

            $response['products'][] = [
                'name' => $product->name,
                'quantity' => $detail->quantity,
                'price' => $product->sel_price,
                'imageUrl' => $imageUrl
            ];
        }

        return $this->successResponse($response, 'Order detail');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_order_price' => 'required|numeric',
            'order_status' => 'required|integer',
            'payment_method' => 'required|integer',
            'payment_status' => 'required|boolean',
            'order_details' => 'required|array',
            'order_details.*.product_variant_id' => 'required|exists:product_variants,id',
            'order_details.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $validatedData['user_id'],
                'total_order_price' => $validatedData['total_order_price'],
                'order_status' => $validatedData['order_status'],
                'payment_method' => $validatedData['payment_method'],
                'payment_status' => $validatedData['payment_status'],
            ]);

            // Thêm chi tiết đơn hàng và cập nhật tồn kho
            foreach ($validatedData['order_details'] as $detail) {
                $productVariant = \App\Models\ProductVariant::find($detail['product_variant_id']);

                if ($productVariant->quantity < $detail['quantity']) {
                    // Trả về lỗi nếu số lượng không đủ
                    return response()->json([
                        'message' => "Sản phẩm '{$productVariant->product->name}' không đủ số lượng."
                    ], 400);
                }

                // Trừ số lượng trong kho
                $productVariant->quantity -= $detail['quantity'];
                $productVariant->save();

                // Tạo chi tiết đơn hàng
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $detail['product_variant_id'],
                    'quantity' => $detail['quantity'],
                ]);
            }

            return response()->json([
                'message' => 'Order created successfully.',
                'order' => $order
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error occurred: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->notFoundResponse('Order not found.');
        }

        try {
            $order->delete();

            return $this->successResponse(null, 'Order deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function updateOrderStatus(Request $request, string $orderId)
    {
        $status = $request->input('order_status'); // 1: Chờ xử lý, 2: Đang vận chuyển, 3:Đã giao, 4: Đã hủy

        try {
            $order = Order::find($orderId);
            if (!$order) {
                return $this->notFoundResponse('Order not found.');
            }

            if ($status == 3) { // Trạng thái "Đã giao"
                $order->payment_status = 1; // Cập nhật trạng thái thanh toán thành đã thanh toán
            }

            // Nếu trạng thái là "Hủy hàng"
            if ($status == 4) {
                // Lấy danh sách các sản phẩm trong đơn hàng
                $orderDetails = $order->orderDetails;

                foreach ($orderDetails as $detail) {
                    $variant = $detail->productVariant;
                    if ($variant) {
                        // Cộng lại số lượng tồn kho
                        $variant->quantity += $detail->quantity;
                        $variant->save();
                    }
                }
            }

            $order->order_status = $status;
            $order->save();

            return $this->successResponse($order, 'Order status updated successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
