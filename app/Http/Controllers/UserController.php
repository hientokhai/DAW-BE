<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Traits\JsonResponse;
use App\Models\User;
class UserController extends Controller
{
    use JsonResponse;
    // Lấy danh sách sản phẩm
    public function index()
    {
        $users = User::all(); // Lấy toàn bộ sản phẩm

        return $this->successResponse(data: $users, message:'Users list.');
    }
    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Tạo người dùng mới
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']), // Mã hóa mật khẩu
        ]);

        return $this->successResponse(data: $user, message: 'User created successfully.');
    }
}