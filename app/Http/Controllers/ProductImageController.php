<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    use JsonResponse;
    // Lấy danh sách sản phẩm
    public function index()
    {
        $images = ProductImage::with('product')->get(); // Lấy toàn bộ sản phẩm

        return $this->successResponse(data: $images, message:'Product-Image list.');
    }

    // Tạo sản phẩm mới
    public function store(Request $request)
    {
        try {
            // Validate rằng ảnh phải là file và có định dạng hợp lệ
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',  // ID sản phẩm phải tồn tại
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Kiểm tra ảnh hợp lệ
            ]);

            // Kiểm tra và lưu ảnh
            if ($request->hasFile('image')) {
                // Upload ảnh vào thư mục public/uploads/products
                $imagePath = $request->file('image')->store('public/uploads/products');

                // Lưu đường dẫn ảnh vào database
                $productImage = ProductImage::create([
                    'product_id' => $validatedData['product_id'],
                    'image_url' => Storage::url($imagePath), // Lấy URL từ thư mục lưu trữ
                ]);

                return response()->json(['message' => 'Image uploaded successfully.', 'data' => $productImage], 200);
            }

            return response()->json(['message' => 'No image file provided.'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}