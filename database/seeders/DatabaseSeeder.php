<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Bảng suppliers
        DB::table('suppliers')->insert([
            ['name' => 'Nhà cung cấp 1', 'address' => 'Address 1', 'phone_number' => '123456789', 'email' => 'supplier1@example.com', 'contact_person' => 'John Doe'],
            ['name' => 'Nhà cung cấp 2', 'address' => 'Address 2', 'phone_number' => '987654321', 'email' => 'supplier2@example.com', 'contact_person' => 'Jane Doe'],
            ['name' => 'Nhà cung cấp 3', 'address' => 'Address 3', 'phone_number' => '555555555', 'email' => 'supplier3@example.com', 'contact_person' => 'Bob Smith'],
        ]);

        // Bảng colors
        DB::table('colors')->insert([
            ['color_name' => 'Đỏ', 'color_code' => '#FF0000'],
            ['color_name' => 'Xanh lá', 'color_code' => '#00FF00'],
            ['color_name' => 'Xanh da trời', 'color_code' => '#0000FF'],
        ]);

        // Bảng sizes
        DB::table('sizes')->insert([
            ['size_name' => 'S', 'description' => '45kg - 55kg'],
            ['size_name' => 'M', 'description' => '55kg - 65kg'],
            ['size_name' => 'L', 'description' => '> 65kg'],
        ]);

        // Bảng site_infos
        DB::table('site_infos')->insert([
            ['shop_name' => 'Shop 1', 'address' => 'Address 1', 'phone_number' => '123456789', 'email' => 'info@shop1.com'],
            ['shop_name' => 'Shop 2', 'address' => 'Address 2', 'phone_number' => '987654321', 'email' => 'info@shop2.com'],
            ['shop_name' => 'Shop 3', 'address' => 'Address 3', 'phone_number' => '555555555', 'email' => 'info@shop3.com'],
        ]);
        // Bảng users
        DB::table('users')->insert([
            ['name' => 'User 1', 'email' => 'user1@gmail.com', 'password' => bcrypt('password1'), 'phone_number' => '123456789', 'address' => 'Address 1', 'role' => 'customer'],
            ['name' => 'User 2', 'email' => 'user2@gmail.com', 'password' => bcrypt('password2'), 'phone_number' => '987654321', 'address' => 'Address 2', 'role' => 'customer'],
            ['name' => 'Quản trị viên', 'email' => 'admin@gmail.com', 'password' => bcrypt('admin'), 'phone_number' => '555555555', 'address' => 'Address 3', 'role' => 'admin'],
        ]);

        // Bảng categories
        DB::table('categories')->insert([
            ['name' => 'Category 1', 'parent_id' => null, 'is_visible' => 1, 'slug' => 'category-1'],
            ['name' => 'Category 2', 'parent_id' => 1, 'is_visible' => 1, 'slug' => 'category-2'],
            ['name' => 'Category 3', 'parent_id' => 2, 'is_visible' => 0, 'slug' => 'category-3'],
        ]);

        // Bảng products
        DB::table('products')->insert([
            ['category_id' => 1, 'name' => 'Product 1', 'ori_price' => 100000, 'sel_price' => 200000, 'description' => 'Description 1', 'is_featured' => 1, 'supplier_id' => 1, 'slug' => 'product-1'],
            ['category_id' => 2, 'name' => 'Product 2', 'ori_price' => 250000, 'sel_price' => 450000, 'description' => 'Description 2', 'is_featured' => 0, 'supplier_id' => 2, 'slug' => 'product-2'],
            ['category_id' => 3, 'name' => 'Product 3', 'ori_price' => 350000, 'sel_price' => 650000, 'description' => 'Description 3', 'is_featured' => 1, 'supplier_id' => 3, 'slug' => 'product-3'],
        ]);

        // Bảng product_variants
        DB::table('product_variants')->insert([
            ['product_id' => 1, 'quantity' => 100, 'size_id' => 1, 'color_id' => 1],
            ['product_id' => 2, 'quantity' => 200, 'size_id' => 2, 'color_id' => 2],
            ['product_id' => 3, 'quantity' => 150, 'size_id' => 3, 'color_id' => 3],
        ]);

        // Bảng product_like_views
        DB::table('product_like_views')->insert([
            ['product_id' => 1, 'user_id' => 1, 'like_count' => 5, 'view_count' => 100],
            ['product_id' => 2, 'user_id' => 2, 'like_count' => 10, 'view_count' => 200],
            ['product_id' => 3, 'user_id' => 2, 'like_count' => 15, 'view_count' => 300],
        ]);

        // Bảng comments
        DB::table('comments')->insert([
            ['comment_text' => 'Comment 1', 'rating' => 5, 'user_id' => 1, 'product_variant_id' => 1],
            ['comment_text' => 'Comment 2', 'rating' => 4, 'user_id' => 2, 'product_variant_id' => 2],
            ['comment_text' => 'Comment 3', 'rating' => 3, 'user_id' => 2, 'product_variant_id' => 3],
        ]);

        // Bảng orders
        DB::table('orders')->insert([
            ['user_id' => 1, 'total_order_price' => 500000, 'order_status' => 1, 'payment_method' => 2, 'payment_status' => 1],
            ['user_id' => 2, 'total_order_price' => 20000, 'order_status' => 2, 'payment_method' => 2, 'payment_status' => 1],
            ['user_id' => 2, 'total_order_price' => 15000, 'order_status' => 1, 'payment_method' => 1, 'payment_status' => 0],
        ]);

        // Bảng order_details
        DB::table('order_details')->insert([
            ['order_id' => 1, 'product_variant_id' => 1, 'quantity' => 2],
            ['order_id' => 2, 'product_variant_id' => 2, 'quantity' => 1],
            ['order_id' => 3, 'product_variant_id' => 3, 'quantity' => 5],
        ]);

        // Bảng product_images
        DB::table('product_images')->insert([
            ['product_id' => 1, 'image_url' => 'https://media3.coolmate.me/cdn-cgi/image/quality=80,format=auto/uploads/November2024/24CMCW.KM005_-_Navy_1.jpg'],
            ['product_id' => 2, 'image_url' => 'https://media3.coolmate.me/cdn-cgi/image/quality=80,format=auto/uploads/November2024/24CMCW.QJ001_-_Reu_1.JPG'],
            ['product_id' => 3, 'image_url' => 'https://media3.coolmate.me/cdn-cgi/image/quality=80,format=auto/uploads/November2024/24CMCW.TN002_-_Be_2.JPG'],
        ]);

        // Bảng contacts
        DB::table('contacts')->insert([
            ['user_id' => 1, 'title' => 'Inquiry 1', 'message' => 'Message 1', 'status' => 1, 'response' => 'Response 1'],
            ['user_id' => 2, 'title' => 'Inquiry 2', 'message' => 'Message 2', 'status' => 1, 'response' => 'Response 2'],
            ['user_id' => 2, 'title' => 'Inquiry 3', 'message' => 'Message 3', 'status' => 0, 'response' => ''],
        ]);


        // Bảng blog_categories
        DB::table('blog_categories')->insert([
            ['name' => 'Blog Category 1'],
            ['name' => 'Blog Category 2'],
            ['name' => 'Blog Category 3'],
        ]);


        // Bảng blogs
        DB::table('blogs')->insert([
            ['title' => 'Blog 1', 'content' => 'Content 1', 'image_url' => 'https://media3.coolmate.me/cdn-cgi/image/quality=80,format=auto/uploads/May2023/tho-hay-ve-tinh-yeu.jpg', 'blog_category_id' => 1],
            ['title' => 'Blog 2', 'content' => 'Content 2', 'image_url' => 'https://media3.coolmate.me/cdn-cgi/image/quality=80,format=auto/uploads/May2023/tho-hay-ve-tinh-yeu.jpg', 'blog_category_id' => 2],
            ['title' => 'Blog 3', 'content' => 'Content 3', 'image_url' => 'https://media3.coolmate.me/cdn-cgi/image/quality=80,format=auto/uploads/May2023/tho-hay-ve-tinh-yeu.jpg', 'blog_category_id' => 3],
        ]);

        // Bảng slides
        DB::table('slides')->insert([
            ['title' => 'Slide 1', 'image_url' => 'https://media3.coolmate.me/cdn-cgi/image/width=1920,quality=90,format=auto/uploads/November2024/Hero_Banner_-_Desktop_(6)_SL_Men_Day.jpg', 'link_url' => 'https://www.coolmate.me/collections?itm_source=homepage&itm_medium=herobanner_1&itm_campaign=Hero_Banner_-_Desktop_(6)_SL_Men_Day&itm_content=/image/November2024/Hero_Banner_-_Desktop_(6)_SL_Men_Day.jpg'],
            ['title' => 'Slide 2', 'image_url' => 'https://media3.coolmate.me/cdn-cgi/image/width=1920,quality=90,format=auto/uploads/November2024/Hero_Banner_-_Desktop_SL_SSS.jpg', 'link_url' => 'https://www.coolmate.me/collections?itm_source=homepage&itm_medium=herobanner_1&itm_campaign=Hero_Banner_-_Desktop_(6)_SL_Men_Day&itm_content=/image/November2024/Hero_Banner_-_Desktop_(6)_SL_Men_Day.jpg'],
            ['title' => 'Slide 3', 'image_url' => 'https://media3.coolmate.me/cdn-cgi/image/width=1920,quality=90,format=auto/uploads/October2024/1920_x_788_hero_banner2.jpg', 'link_url' => 'https://www.coolmate.me/collections?itm_source=homepage&itm_medium=herobanner_1&itm_campaign=Hero_Banner_-_Desktop_(6)_SL_Men_Day&itm_content=/image/November2024/Hero_Banner_-_Desktop_(6)_SL_Men_Day.jpg'],
        ]);
    }
}