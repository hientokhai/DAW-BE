<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use JsonResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with([
            'user'
        ])
            ->orderByDesc('created_at')
            ->get();

        $data = $orders->map(function ($order) {
            return [
                "id" => $order->id,
                "customer_name" => $order->user->name,
                "total_order_price" => (double) $order->total_order_price,
                "order_status" => (int) $order->order_status,
                "payment_method" => (int) $order->payment_method,
                "payment_status" => $order->payment_status,
                "created_at" => $order->created_at,
            ];
        });

        return $this->successResponse($data, 'List order');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}