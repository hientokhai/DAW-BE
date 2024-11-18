<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use JsonResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy tất cả các danh mục từ cơ sở dữ liệu
    $categories = Category::orderByDesc('created_at')->get();

    // Kiểm tra xem có danh mục nào không
    if ($categories->isEmpty()) {
        return $this->errorResponse('No categories found', 404);
    }

    // Xử lý dữ liệu để định dạng
    $data = $categories->map(function ($category) {
        return [
            "id" => $category->id,
            "name" => $category->name,
            "slug" => $category->slug,
            "parent_id" => $category->parent_id,
            "is_visible" => $category->is_visible,
            "created_at" => $category->created_at,
            "updated_at" => $category->updated_at,
        ];
    });

    return $this->successResponse($data, 'List of categories');

        }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    // Có thể hiển thị form tạo danh mục mới ở đây
}

public function store(Request $request)
{
    // Xác thực dữ liệu đầu vào
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories',
        'parent_id' => 'nullable|exists:categories,id',
        'is_visible' => 'boolean',
    ]);

    // Tạo danh mục mới
    $category = Category::create($request->only('name', 'slug', 'parent_id', 'is_visible'));

    return $this->successResponse($category, 'Category created successfully', 201);
}

public function show(string $id)
{
    // Tìm danh mục theo ID
    $category = Category::findOrFail($id);
    return $this->successResponse($category, 'Category retrieved successfully');
}

public function edit(string $id)
{
    // Có thể hiển thị form chỉnh sửa danh mục ở đây
}

public function update(Request $request, string $id)
{
    // Tìm danh mục theo ID
    $category = Category::findOrFail($id);

    // Xác thực dữ liệu đầu vào
    $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'slug' => 'sometimes|required|string|max:255|unique:categories,slug,' . $category->id,
        'parent_id' => 'nullable|exists:categories,id',
        'is_visible' => 'boolean',
    ]);

    // Cập nhật danh mục
    $category->update($request->only('name', 'slug', 'parent_id', 'is_visible'));

    return $this->successResponse($category, 'Category updated successfully');
}

public function destroy(string $id)
{
    // Tìm danh mục theo ID
    $category = Category::findOrFail($id);

    // Xóa danh mục
    $category->delete();

    return $this->successResponse(null, 'Category deleted successfully');
}

public function search(Request $request)
{
    // Xác thực dữ liệu đầu vào
    $request->validate([
        'query' => 'required|string|max:255',
    ]);

    // Tìm kiếm danh mục theo tên hoặc slug
    $categories = Category::where('name', 'like', '%' . $request->query . '%')
        ->orWhere('slug', 'like', '%' . $request->query . '%')
        ->orderByDesc('created_at')
        ->get();

    // Kiểm tra xem có danh mục nào không
    if ($categories->isEmpty()) {
        return $this->errorResponse('No categories found', 404);
    }

    // Xử lý dữ liệu để định dạng
    $data = $categories->map(function ($category) {
        return [
            "id" => $category->id,
            "name" => $category->name,
            "slug" => $category->slug,
            "parent_id" => $category->parent_id,
            "is_visible" => $category->is_visible,
            "created_at" => $category->created_at,
            "updated_at" => $category->updated_at,
        ];
    });

    return $this->successResponse($data, 'Search results for categories');
}
}