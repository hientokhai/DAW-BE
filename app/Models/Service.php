<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // Định nghĩa bảng mà model này liên kết
    protected $table = 'services';

    // Các cột có thể được gán giá trị (mass assignable)
    protected $fillable = [
        'name', 'icon',
    ];
}
