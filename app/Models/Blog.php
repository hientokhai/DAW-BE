<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BlogCategory;
use App\Models\Product;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'content',
        'image_url',
        'blog_category_id',
        'product_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function categorys_blog()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id', 'id');
    }

    // Quan hệ với Product (nếu có)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}