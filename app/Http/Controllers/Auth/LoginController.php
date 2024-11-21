<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Kiểm tra đầu vào
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 400);
        }

        // Tìm người dùng trong cơ sở dữ liệu
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Không tìm thấy user với email này.'
            ], 404);
        }

        // Kiểm tra mật khẩu
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Mật khẩu sai.'
            ], 401);
        }

        // Tạo token nếu đăng nhập thành công
        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công !',
            'user' => $user,
            'token' => $token
        ]);
    }
}