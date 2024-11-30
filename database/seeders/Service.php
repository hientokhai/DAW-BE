<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Service extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'name' => 'Vận Chuyển Nhanh Chóng',
                'icon' => 'fa-code', // Icon Font Awesome
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Miễn Phí Ship Cho Hóa Đơn Trên 1tr',
                'icon' => 'fa-code', // Icon Font Awesome
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thanh Toán Dễ Dàng',
                'icon' => 'fa-code', // Icon Font Awesome
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hỗ Trợ 24/7',
                'icon' => 'fa-code', // Icon Font Awesome
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}