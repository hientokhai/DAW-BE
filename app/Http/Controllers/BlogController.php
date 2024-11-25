<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Traits\JsonResponse;

class BlogController extends Controller
{
    use JsonResponse;

    // Hàm lấy danh sách tất cả các blog
    public function index()
    {
        $blog = Blog::with(['categorys_blog'])->get();; // Lấy toàn bộ blog

        return $this->successResponse(data: $blog, message: 'Blogs list.');
    }

    // Hàm lấy thông tin chi tiết của blog
    public function show($id)
    {
        // Tìm blog theo ID
        $blog = Blog::find($id);

        // Kiểm tra nếu không tìm thấy blog
        if (!$blog) {
            return $this->errorResponse(message: 'Blog not found.');
        }

        // Trả về thông tin chi tiết của blog
        return $this->successResponse(data: $blog, message: 'Blog details.');
    }
}