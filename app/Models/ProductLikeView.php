<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductLikeView extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_like_views';

    protected $fillable = [
        'product_id',
        'like_count',
        'view_count',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}