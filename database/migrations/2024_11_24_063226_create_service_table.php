<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('services', function (Blueprint $table) {
        $table->id(); // Tạo cột id tự động tăng
        $table->string('name'); // Tạo cột name, kiểu chuỗi
        $table->string('icon'); // Tạo cột icon, kiểu chuỗi
        $table->timestamps(); // Tạo các cột created_at và updated_at
    });
}

public function down()
{
    Schema::dropIfExists('services');
}

};
