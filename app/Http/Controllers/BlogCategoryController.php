<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Traits\JsonResponse;

class BlogCategoryController extends Controller
{
    use JsonResponse;

    // Hàm lấy danh sách tất cả các blog
    public function index()
    {
        $blog = BlogCategory::all(); // Lấy toàn bộ blog

        return $this->successResponse(data: $blog, message: 'BlogCategory list.');
    }
}