<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'ori_price',
        'sel_price',
        'description',
        'slug'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productImages()
{
    return $this->hasMany(ProductImage::class);
}

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function comments()
    {
        // Lấy tất cả các đánh giá của các sản phẩm từ các ProductVariant
        return $this->hasManyThrough(Comment::class, ProductVariant::class);
    }

    public function productLikeViews()
    {
        return $this->hasMany(ProductLikeView::class);
    }
}