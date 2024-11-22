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
     */
    public function index()
    {
        // Lấy danh sách liên hệ kèm thông tin người dùng
        $contacts = Contact::with('user')->get();
    
        return $this->successResponse($contacts, 'Contact list.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate dữ liệu gửi lên từ client
            $validatedData = $request->validate([
                'user_id' => 'nullable|integer',
                'title' => 'nullable|string|max:255',
                'message' => 'required|string',
                'status' => 'nullable|in:replied,unreplied',
            ]);

            // Tạo mới thông tin liên hệ
            $contact = Contact::create($validatedData);

            return $this->successResponse($contact, 'Contact created successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Tìm thông tin liên hệ theo ID
            $contact = Contact::find($id);
            if (!$contact) {
                return $this->errorResponse('Contact not found.');
            }

            // Validate dữ liệu gửi lên
            $validatedData = $request->validate([
                'response' => 'required|string',
            ]);

            // Cập nhật thông tin liên hệ
            $contact->update($validatedData);
            $contact->status=true;
            $contact->save();

            return $this->successResponse($contact, 'Contact updated successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Tìm thông tin liên hệ theo ID
            $contact = Contact::find($id);

            if (!$contact) {
                return $this->errorResponse('Contact not found.');
            }

            // Xóa thông tin liên hệ
            $contact->delete();

            return $this->successResponse([], 'Contact deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
