<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Color;
use App\Models\Comment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductLikeView;
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
        $products = Product::with(['images', 'productVariants', 'category'])->get(); // Lấy toàn bộ sản phẩm
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

            ProductLikeView::where('product_id', $product->id)->delete();

            Comment::whereHas('productVariant', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->delete();

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
            $categories = Category::whereNotNull('parent_id')->get();

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

    public function getByIdClient($id)
    {
        try {
            $product = Product::with(['images', 'productVariants.size', 'productVariants.color', 'category'])->find($id);

            if (!$product) {
                return $this->notFoundResponse('Not found.');
            }

            // Tính số trung bình đánh giá
            $averageRating = $product->comments->avg('rating') ?? 0;
            $totalReviews = $product->comments->count();
            $totalLikes = $product->productLikeViews->sum('like_count');

            // Lấy dữ liệu sizes và colors từ các variants của sản phẩm
            $uniqueSizes = $product->productVariants
                ->pluck('size')
                ->unique('id')
                ->values()
                ->map(function ($size) {
                    return [
                        'id' => $size->id,
                        'size_name' => $size->size_name,
                        'description' => $size->description,
                    ];
                });

            $uniqueColors = $product->productVariants
                ->pluck('color')
                ->unique('id')
                ->values()
                ->map(function ($color) {
                    return [
                        'id' => $color->id,
                        'color_name' => $color->color_name,
                        'color_code' => $color->color_code,
                    ];
                });

            // Chuẩn bị dữ liệu trả về
            $productData = [
                'name' => $product->name,
                'category_id' => $product->category->id ?? null,
                'productVariants' => $product->productVariants->map(function ($variant) {
                    return [
                        'size_id' => $variant->size_id,
                        'color_id' => $variant->color_id,
                        'quantity' => $variant->quantity,
                        'size_name' => $variant->size->size_name,
                        'color_name' => $variant->color->color_name,
                        'color_code' => $variant->color->color_code,
                    ];
                }),
                'ori_price' => strval($product->ori_price),
                'sel_price' => strval($product->sel_price),
                'description' => $product->description,
                'images' => $product->images->pluck('image_url')->toArray(),
                'average_rating' => $averageRating,  // Trả về số trung bình đánh giá
                'total_reviews' => $totalReviews,    // Trả về tổng số đánh giá
                'total_likes' => $totalLikes        // Trả về tổng số lượt thích
            ];

            return $this->successResponse([
                'product' => $productData,
                'sizes' => $uniqueSizes,
                'colors' => $uniqueColors,
            ], 'Chi tiết sản phẩm.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function getListClient(Request $request)
    {
        try {
            // Lấy các tham số lọc từ request
            $searchQuery = $request->query('searchQuery', '');
            $selectedCategory = $request->query('category', '');
            $selectedColor = $request->query('color', '');
            $selectedSize = $request->query('size', '');
            $selectedPrice = $request->query('price', '');
            $sortBy = $request->query('sortBy', 'asc'); // Mặc định sắp xếp tăng dần theo giá

            // Query cơ bản
            $products = Product::with(['images', 'category', 'productVariants.size', 'productVariants.color'])
                ->where('name', 'like', '%' . $searchQuery . '%'); // Tìm kiếm theo tên

            // Lọc theo danh mục
            if ($selectedCategory) {
                $products->whereHas('category', function ($query) use ($selectedCategory) {
                    $query->where('name', $selectedCategory);
                });
            }

            // Lọc theo màu sắc
            if ($selectedColor) {
                $products->whereHas('productVariants.color', function ($query) use ($selectedColor) {
                    $query->where('color_name', $selectedColor);
                });
            }

            // Lọc theo kích thước
            if ($selectedSize) {
                $products->whereHas('productVariants.size', function ($query) use ($selectedSize) {
                    $query->where('size_name', $selectedSize);
                });
            }

            // Lọc theo giá
            if ($selectedPrice === 'below350') {
                $products->where('sel_price', '<', 350000);
            } elseif ($selectedPrice === 'between350And750') {
                $products->whereBetween('sel_price', [350000, 750000]);
            } elseif ($selectedPrice === 'above750') {
                $products->where('sel_price', '>', 750000);
            }

            // Sắp xếp theo giá
            if ($sortBy === 'asc') {
                $products->orderBy('sel_price', 'asc');
            } else {
                $products->orderBy('sel_price', 'desc');
            }

            // Lấy dữ liệu
            $productList = $products->get();

            // Xử lý dữ liệu trả về
            $productData = $productList->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->sel_price,
                    'ori_price' => $product->ori_price,
                    'imgUrl' => $product->images->first()->image_url ?? null, // Lấy ảnh đầu tiên
                    'category' => $product->category->name ?? null,
                    'color' => $product->productVariants->pluck('color.color_code')->unique()->values(),
                    'size' => $product->productVariants->pluck('size.size_name')->unique()->values(),
                ];
            });

            return $this->successResponse($productData, 'Product list');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }


}