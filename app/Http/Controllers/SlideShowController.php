<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;

class SlideshowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        // Lấy tất cả các slide từ cơ sở dữ liệu
        $slides = Slide::orderByDesc('created_at')->get();

        // Kiểm tra xem có slide nào không
        if ($slides->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No slides found',
                'data' => []
            ], 404);
        }

        // Xử lý dữ liệu để định dạng
        $data = $slides->map(function ($slide) {
            return [
                "id" => $slide->id,
                "title" => $slide->title,
                "image_url" => $slide->image_url,
                "link_url" => $slide->link_url,
                "description" => $slide->description,
                "created_at" => $slide->created_at,
                "updated_at" => $slide->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List of slides',
            'data' => $data
        ]);
    }
}