<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Traits\JsonResponse;

class ServiceController extends Controller
{
    // Phương thức để hiển thị danh sách các dịch vụ
    public function index()
    {
        $services = Service::all();
        return response()->json($services);
    }

    // Phương thức để tạo một dịch vụ mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
        ]);

        $service = Service::create([
            'name' => $request->name,
            'icon' => $request->icon,
        ]);

        return response()->json($service, 201); // Trả về dịch vụ đã tạo
    }

    // Phương thức để hiển thị thông tin một dịch vụ
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    // Phương thức để cập nhật một dịch vụ
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->update([
            'name' => $request->name,
            'icon' => $request->icon,
        ]);

        return response()->json($service);
    }

    // Phương thức để xóa một dịch vụ
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(null, 204);
    }
}
