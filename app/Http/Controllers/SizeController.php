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
        $sizes = Size::all();
        return response()->json($sizes);
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
}