<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SlideshowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
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
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List of slides',
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'link_url' => 'nullable|url',
        ]);

        // Tạo slide mới
        $slide = Slide::create($request->only('title', 'image_url', 'link_url', 'description'));

        return response()->json([
            'success' => true,
            'message' => 'Slide created successfully',
            'data' => $slide
        ], 201);
    }
}