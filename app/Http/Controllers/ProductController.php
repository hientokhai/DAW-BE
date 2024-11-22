<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Log;

class ProductController extends Controller
{
    use JsonResponse;

    // Lấy danh sách sản phẩm
    public function index()
    {
        $products = Product::with(['images', 'productVariants'])->get(); // Lấy toàn bộ sản phẩm
        return $this->successResponse(data: $products, message: 'Product list.');
    }

    // Tạo sản phẩm mới
    // public function store(Request $request)
    // {
    //     try {
    //         // Validate the request data
    //         $validatedData = $request->validate([
    //             'category_id' => 'required|integer|exists:categories,id', // Phải tồn tại trong bảng categories
    //             'name' => 'required|string|max:255',
    //             'ori_price' => 'required|numeric|min:0', // Giá gốc
    //             'sel_price' => 'required|numeric|min:0', // Giá bán
    //             'description' => 'nullable|string',
    //             'images' => 'nullable|array',
    //             'images.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation cho file ảnh
    //             'productVariants' => 'nullable|array',
    //             'productVariants.*.quantity' => 'required|integer|min:0',
    //             'productVariants.*.size_id' => 'required|exists:sizes,id',
    //             'productVariants.*.color_id' => 'required|exists:colors,id',
    //         ]);
    //         // Tạo slug từ name
    //         $validatedData['slug'] = Str::slug($request->name, '-');

    //         $product = Product::create($validatedData);

    //         if ($request->has('images')) {
    //             foreach ($request->file('images') as $image) {
    //                 // Upload hình ảnh lên Cloudinary và lấy URL trả về
    //                 $uploadResult = Cloudinary::upload($image->getRealPath(), [
    //                     'folder' => 'products',
    //                 ]);

    //                 // Lưu vào bảng ProductImage với URL từ Cloudinary
    //                 ProductImage::create([
    //                     'product_id' => $product->id,
    //                     'image_url' => $uploadResult->getSecurePath(), // Lấy URL bảo mật từ Cloudinary
    //                 ]);
    //             }
    //         }

    //         if ($request->has('productVariants')) {
    //             foreach ($request->productVariants as $variant) {
    //                 ProductVariant::create([
    //                     'product_id' => $product->id,
    //                     'quantity' => $variant['quantity'],
    //                     'size_id' => $variant['size_id'],
    //                     'color_id' => $variant['color_id'],
    //                 ]);
    //             }
    //         }

    //         return $this->successResponse($product, 'Product created successfully.');
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage());
    //     }
    // }

    public function getById($id)
    {
        try {
            $product = Product::with(['images', 'productVariants', 'category'])->find($id);

            if (!$product) {
                return $this->notFoundResponse('Product not found.');
            }

            $productData = [
                'name' => $product->name,
                'category_id' => $product->category->id ?? null,
                'productVariants' => $product->productVariants->map(function ($variant) {
                    return [
                        'size_id' => $variant->size_id,
                        'color_id' => $variant->color_id,
                        'quantity' => $variant->quantity
                    ];
                }),
                'ori_price' => strval($product->ori_price),
                'sel_price' => strval($product->sel_price),
                'description' => $product->description,
                'images' => $product->images->pluck('image_url')->toArray(),
            ];

            $mockCategories = Category::all()->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name
                ];
            });

            $mockSizes = Size::all()->map(function ($size) {
                return [
                    'id' => $size->id,
                    'size_name' => $size->size_name
                ];
            });

            $mockColors = Color::all()->map(function ($color) {
                return [
                    'id' => $color->id,
                    'color_name' => $color->color_name
                ];
            });

            return $this->successResponse([
                'product' => $productData,
                'categories' => $mockCategories,
                'sizes' => $mockSizes,
                'colors' => $mockColors
            ], 'Product details.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return $this->errorResponse('Product not found.', 404);
            }

            // Validate dữ liệu
            $validatedData = $request->validate([
                'category_id' => 'required|integer|exists:categories,id',
                'name' => 'required|string|max:255',
                'ori_price' => 'required|numeric|min:0', // Giá gốc
                'sel_price' => 'required|numeric|min:0', // Giá bán
                'description' => 'nullable|string',
                'images' => 'nullable|array',
                'images.*' => 'nullable|url',
                'productVariants' => 'nullable|array',
                'productVariants.*.quantity' => 'nullable|integer|min:0',
                'productVariants.*.size_id' => 'nullable|exists:sizes,id',
                'productVariants.*.color_id' => 'nullable|exists:colors,id',
            ]);

            // Cập nhật thông tin sản phẩm
            $product->update([
                'category_id' => $validatedData['category_id'],
                'name' => $validatedData['name'],
                'ori_price' => $validatedData['ori_price'],
                'sel_price' => $validatedData['sel_price'],
                'description' => $validatedData['description'] ?? $product->description, // Nếu không có description mới thì giữ nguyên
            ]);

            // Xử lý cập nhật hình ảnh (nếu có)
            if ($request->has('images')) {
                ProductImage::where('product_id', $product->id)->delete();

                foreach ($validatedData['images'] as $imageUrl) {
                    if ($imageUrl) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_url' => $imageUrl,
                        ]);
                    }
                }
            }

            // Xử lý cập nhật biến thể sản phẩm (nếu có)
            if ($request->has('productVariants')) {
                // Xóa tất cả biến thể cũ trước khi thêm biến thể mới
                ProductVariant::where('product_id', $product->id)->delete();

                foreach ($validatedData['productVariants'] as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'quantity' => $variant['quantity'],
                        'size_id' => $variant['size_id'],
                        'color_id' => $variant['color_id'],
                    ]);
                }
            }

            // Trả về thông báo thành công với dữ liệu sản phẩm đã cập nhật
            return $this->successResponse($product->fresh(), 'Product updated successfully.');
        } catch (\Exception $e) {
            // Xử lý lỗi và trả về thông báo lỗi
            // Log::error('Error updating product: ' . $e->getMessage());
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return $this->notFoundResponse('Product not found.');
            }

            ProductImage::where('product_id', $product->id)->delete();
            ProductVariant::where('product_id', $product->id)->delete();
            $product->delete();

            return $this->successResponse(null, 'Product deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function searchProduct(Request $request)
    {
        try {
            $name = $request->input('name');

            $query = Product::query();

            // if ($categoryName) {
            //     $query->where('category_name', 'LIKE', "%$categoryName%");
            // }

            if ($name) {
                $query->where('name', 'LIKE', "%$name%");
            }

            $products = $query->with(['images', 'productVariants'])->get();

            return $this->successResponse($products, 'Search results.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function getCategoriesAndVariants()
    {
        try {
            $categories = Category::all();

            $sizes = Size::all();

            $colors = Color::all();

            return $this->successResponse([
                'categories' => $categories,
                'sizes' => $sizes,
                'colors' => $colors
            ], 'variant list.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate dữ liệu
            $validatedData = $request->validate([
                'category_id' => 'required|integer|exists:categories,id',
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'ori_price' => 'required|numeric|min:0', // Giá gốc
                'sel_price' => 'required|numeric|min:0', // Giá bán
                'description' => 'nullable|string',
                'images' => 'nullable|array',
                'images.*' => 'nullable|url',
                'productVariants' => 'nullable|array',
                'productVariants.*.quantity' => 'nullable|integer|min:0',
                'productVariants.*.size_id' => 'nullable|exists:sizes,id',
                'productVariants.*.color_id' => 'nullable|exists:colors,id',
            ]);

            // Tạo sản phẩm mới
            $product = Product::create([
                'category_id' => $validatedData['category_id'],
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'],
                'ori_price' => $validatedData['ori_price'],
                'sel_price' => $validatedData['sel_price'],
                'description' => $validatedData['description'] ?? null,
            ]);

            // Xử lý lưu hình ảnh sản phẩm
            if ($request->has('images')) {
                foreach ($validatedData['images'] as $imageUrl) {
                    if ($imageUrl) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_url' => $imageUrl,
                        ]);
                    }
                }
            }

            // Xử lý lưu biến thể sản phẩm (nếu có)
            if ($request->has('productVariants')) {
                foreach ($validatedData['productVariants'] as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'quantity' => $variant['quantity'],
                        'size_id' => $variant['size_id'],
                        'color_id' => $variant['color_id'],
                    ]);
                }
            }

            return $this->successResponse($product, 'Product created successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

}