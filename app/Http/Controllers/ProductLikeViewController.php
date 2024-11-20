<?php

namespace App\Http\Controllers;

use App\Models\ProductLikeView;
use App\Traits\JsonResponse;

class ProductLikeViewController extends Controller
{
    use JsonResponse;
    // Lấy danh sách sản phẩm
    public function index()
    {
        $products = ProductLikeView::all(); // Lấy toàn bộ sản phẩm

        return $this->successResponse(data: $products, message:'Product-Like-View list.');
    }
}