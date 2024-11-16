<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('total_order_price', 10, 2);
            $table->integer('order_status')->default(1);//1: Chờ xử lý, 2: Đang giao hàng, 3: Đã giao, 4: Đã hủy
            $table->integer('payment_method');//1: COD, 2: online
            $table->boolean('payment_status')->default(false);//true: Đã thanh toán, false: Chưa thanh toán
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};