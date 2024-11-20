<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductController extends Controller
{
    use JsonResponse;

    // Lấy danh sách sản phẩm
    public function index()
    {
        $products = Product::with(['images', 'productVariants'])->get(); // Lấy toàn bộ sản phẩm
        return $this->successResponse(data: $products, message:'Product list.');
    }

    // Tạo sản phẩm mới
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'category_id' => 'required|integer|exists:categories,id', // Phải tồn tại trong bảng categories
                'name'        => 'required|string|max:255',
                'ori_price'   => 'required|numeric|min:0', // Giá gốc
                'sel_price'   => 'required|numeric|min:0', // Giá bán
                'description' => 'nullable|string',
                'images' => 'nullable|array',
                'images.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation cho file ảnh
                'productVariants' => 'nullable|array',
                'productVariants.*.quantity' => 'required|integer|min:0',
                'productVariants.*.size_id' => 'required|exists:sizes,id',
                'productVariants.*.color_id' => 'required|exists:colors,id',
            ]);
            // Tạo slug từ name
            $validatedData['slug'] = Str::slug($request->name, '-');

            $product = Product::create($validatedData);

            if ($request->has('images')) {
                foreach ($request->file('images') as $image) {
                    // Upload hình ảnh lên Cloudinary và lấy URL trả về
                    $uploadResult = Cloudinary::upload($image->getRealPath(), [
                        'folder' => 'products',
                    ]);
    
                    // Lưu vào bảng ProductImage với URL từ Cloudinary
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url'  => $uploadResult->getSecurePath(), // Lấy URL bảo mật từ Cloudinary
                    ]);
                }
            }    

            if ($request->has('productVariants')) {
                foreach ($request->productVariants as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'quantity'   => $variant['quantity'],
                        'size_id'    => $variant['size_id'],
                        'color_id'   => $variant['color_id'],
                    ]);
                }
            }

            return $this->successResponse($product, 'Product created successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}