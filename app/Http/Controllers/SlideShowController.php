<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SlideshowController extends Controller
{
    public function index(): JsonResponse
    {
        $slides = Slide::orderByDesc('created_at')->get();

        if ($slides->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No slides found',
                'data' => []
            ], 404);
        }

        $data = $slides->map(function ($slide) {
            return [
                "id" => $slide->id,
                "title" => $slide->title,
                "image_url" => $slide->image_url,
                'link_url' => $slide->link_url,
                "description" => $slide->description,
                "is_active" => $slide->is_active,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List of slides',
            'data' => $data
        ]);
    }

    public function store(Request $request): JsonResponse
    {
            try {
            // Validate the request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Hình ảnh là bắt buộc
                'link_url' => 'nullable|url', // Đường dẫn hình ảnh là tùy chọn
                'is_active' => 'nullable|boolean'
            ]);


            // Upload hình ảnh lên Cloudinary và lấy URL trả về
            $uploadResult = Cloudinary::upload($request->file('image_url')->getRealPath(), [
                'folder' => 'slides',
            ]);

            // Lưu URL hình ảnh vào validatedData
            $validatedData['image_url'] = $uploadResult->getSecurePath(); // Lấy URL bảo mật từ Cloudinary

            // Tạo mới slide
            $slide = Slide::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Slide created successfully',
                'data' => $slide
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_url' => 'nullable|url',
            'is_active' => 'nullable|boolean'
        ]);

        $slide = Slide::find($id);

        if (!$slide) {
            return response()->json([
                'success' => false,
                'message' => 'Slide not found',
            ], 404);
        }

        $slide->update($request->only('title', 'description', 'is_active'));

        if ($request->hasFile('image_url')) {
            $slide->image_url = $this->uploadImage($request->file('image_url'));
        }

        $slide->save();

        return response()->json([
            'success' => true,
            'message' => 'Slide updated successfully',
            'data' => $slide
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $slide = Slide::find($id);

        if (!$slide) {
            return response()->json([
                'success' => false,
                'message' => 'Slide not found',
            ], 404);
        }

        $slide->delete();

        return response()->json([
            'success' => true,
            'message' => 'Slide deleted successfully',
        ]);
    }

}