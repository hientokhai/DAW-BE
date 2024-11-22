<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    use JsonResponse;
    public function index(Request $request)
    {
        $query = Order::query();

        // Lọc theo ngày nếu có tham số
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->get();
        $total = 0;
        $buy = 0;

        foreach ($orders as $item) {
            $total += $item->total_order_price;

            if ($item->payment_status) {
                $buy += 1;
            }
        }

        return $this->successResponse([
            'total_revenue' => number_format($total, 0, ',', '.'),
            'total_orders' => $buy,
        ]);
    }
}
