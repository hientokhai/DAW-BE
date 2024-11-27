<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Contact;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use JsonResponse;

    /**
     * Display a listing of the resource.
     * Lấy danh sách liên hệ.
     */
    public function index()
    {
        // Lấy danh sách liên hệ kèm thông tin người dùng
        $contacts = Contact::with('user')->orderBy('created_at', 'desc')->get();
    
        return $this->successResponse($contacts, 'Danh sách liên hệ.');
    }

    /**
     * Store a newly created resource in storage.
     * Lưu thông tin liên hệ từ client.
     */
    public function store(Request $request)
    {
        try {
            // Validate dữ liệu gửi lên từ client
            $validatedData = $request->validate([
                'user_id' => 'nullable|integer', // ID người dùng (có thể null nếu không đăng nhập)
                'title' => 'nullable|string|max:255', // Tiêu đề liên hệ
                'message' => 'required|string', // Nội dung liên hệ (bắt buộc)
                'status' => 'nullable|in:pending,replied', // Trạng thái (mặc định là "pending")
            ]);

            // Gán giá trị mặc định nếu không có
            if (!isset($validatedData['status'])) {
                $validatedData['status'] = 'pending';
            }

            // Tạo mới thông tin liên hệ
            $contact = Contact::create($validatedData);

            return $this->successResponse($contact, 'Tạo liên hệ thành công.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * Cập nhật phản hồi liên hệ.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Tìm thông tin liên hệ theo ID
            $contact = Contact::find($id);
            if (!$contact) {
                return $this->errorResponse('Không tìm thấy thông tin liên hệ.');
            }

            // Validate dữ liệu gửi lên
            $validatedData = $request->validate([
                'response' => 'required|string', // Phản hồi (bắt buộc)
                'status' => 'nullable|in:pending,replied', // Trạng thái (nếu có)
            ]);

            // Cập nhật thông tin liên hệ
            $contact->update([
                'response' => $validatedData['response'],
                'status' => $validatedData['status'] ?? 'replied', // Mặc định là "replied" nếu không gửi
            ]);

            return $this->successResponse($contact, 'Cập nhật phản hồi thành công.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove method removed.
     * Không cho phép xóa thông tin liên hệ.
     */
}
