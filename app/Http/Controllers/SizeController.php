<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    // Get a list of sizes
    public function index()
    {
        // Lấy tất cả kích thước từ cơ sở dữ liệu
        $sizes = Size::all();

        // Sử dụng map để định dạng dữ liệu
        $data = $sizes->map(function ($size) {
            return [
                "id" => $size->id,
                "size_name" => $size->size_name,
                "description" => $size->description,
                "created_at" => $size->created_at ? $size->created_at->format('d/m/Y') : 'N/A',
            ];
        });

        // Trả về phản hồi JSON với dữ liệu đã định dạng
        return response()->json($data);
    }
    // Thêm một kích thước mới
    public function store(Request $request)
    {
        $request->validate([
            'size_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $size = Size::create($request->all());
        return response()->json($size, 201);
    }
     // Cập nhật thông tin một kích thước theo ID
     public function update(Request $request, $id)
    {
        $request->validate([
            'size_name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
        ]);

        $size = Size::findOrFail($id);
        $size->update($request->all());
        return response()->json($size, 200);
    }
}