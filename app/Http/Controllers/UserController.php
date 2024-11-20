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
}