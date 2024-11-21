<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        $total = 0;

        foreach( $orders as $item )
        {
            $total += $item->total_order_price;
        }
        return response()->json([
            'turnover'=> $total, //doanh thu
        ]);
    }
}
