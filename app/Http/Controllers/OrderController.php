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
            'user'
        ])
            // ->orderByDesc('created_at')
            ->get();

        $data = $orders->map(function ($order) {
            return [
                "id" => $order->id,
                "customer_name" => $order->user->name,
                "total_order_price" => (double) $order->total_order_price,
                "order_status" => (int) $order->order_status,
                "payment_method" => (int) $order->payment_method,
                "payment_status" => $order->payment_status,
                "created_at" => $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A',
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
        //
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
        $status = $request->input('order_status');// 1: Chờ xử lý, 2: Đang vận chuyển, 3:Đã giao, 4: Đã hủy

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