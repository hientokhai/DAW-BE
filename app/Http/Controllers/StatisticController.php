<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    use JsonResponse;
    public function index()
    {
        $orders = Order::all();
        $total = 0;
        $buy = 0;

        foreach( $orders as $item )
        {
            $total += $item->total_order_price;

            if($item->payment_status)
            {
                $buy += 1;
            }
        }

        return $this->successResponse([$total, $buy]);
    }
}
