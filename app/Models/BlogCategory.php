<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Blog;

class BlogCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_categories';

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
        // Quan hệ với Blog
        public function blogs()
        {
            return $this->hasMany(Blog::class, 'blog_category_id');
        }
}