<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
class ProductVariantController extends Controller
{
    use JsonResponse;
    // Lấy danh sách sản phẩm
    public function index()
    {
        $variants = ProductVariant::with('product')->get(); // Lấy toàn bộ sản phẩm

        return $this->successResponse(data: $variants, message:'Product-Variant list.');
    }

    public function store(Request $request)
    {
        try{
                    // Validate dữ liệu đầu vào
            $validatedData = $request->validate([
                'product_id' => 'required|integer|exists:products,id', // Phải tồn tại trong bảng products
                'quantity'        => 'required|numeric',
                'size_id'   => 'required|integer|exists:sizes,id', 
                'color_id'   => 'required|integer|exists:colors,id',
            ]);

            // Tạo sản phẩm mới
            $variants = ProductVariant::create($validatedData);

            return $this->successResponse(data: $variants, message:'Product-Variant created successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}