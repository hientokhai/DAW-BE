<?php

namespace App\Http\Controllers;

use App\Models\SiteInfo;
use Illuminate\Http\Request;
use App\Traits\JsonResponse;

class SiteInfoController extends Controller
{
    use JsonResponse; // Sử dụng trait JsonResponse

    // Lấy danh sách thông tin site
    public function index()
    {
        try {
            // Lấy thông tin đầu tiên
            $siteInfo = SiteInfo::first();
    
            // Kiểm tra nếu không có dữ liệu
            if (!$siteInfo) {
                return $this->errorResponse('No site information found.', 404);
            }
    
            // Trả về dữ liệu theo mẫu
            $response = [
                'id' => $siteInfo->id,
                'shop_name' => $siteInfo->shop_name,
                'address' => $siteInfo->address,
                'phone_number' => $siteInfo->phone_number,
                'email' => $siteInfo->email,
                'description' => $siteInfo->description,
                'logo_header_url' => $siteInfo->logo_header_url,
                'logo_footer_url' => $siteInfo->logo_footer_url,
                'social_facebook' => $siteInfo->social_facebook,
                'social_instagram' => $siteInfo->social_instagram,
                'social_twitter' => $siteInfo->social_twitter,
                'social_linkedin' => $siteInfo->social_linkedin,
            ];
    
            return $this->successResponse($response, 'Site information retrieved successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
    
    // Cập nhật thông tin site
    public function update(Request $request, $id)
    {
        try {
            $siteInfo = SiteInfo::find($id);
            if (!$siteInfo) {
                return $this->errorResponse('Site not found.');
            }

            // Xác nhận và validate dữ liệu
            $validatedData = $request->validate([
                'shop_name' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'phone_number' => 'nullable|string|max:15',
                'email' => 'nullable|email|max:255',
                'description' => 'nullable|string',
                'logo_header_url' => 'nullable|string',
                'logo_footer_url' => 'nullable|string',
                'social_facebook' => 'nullable|url',
                'social_instagram' => 'nullable|url',
                'social_twitter' => 'nullable|url',
                'social_linkedin' => 'nullable|url',
            ]);

            // Cập nhật thông tin site
            $siteInfo->update($validatedData);

            return $this->successResponse($siteInfo, 'Site information updated.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

   
            
}
