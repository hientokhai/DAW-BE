<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contacts';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'status',
        'response',
        'created_at',
    ];

    // Bỏ 'created_at' khỏi $hidden nếu bạn muốn trả về trường này trong API response
    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    /**
     * Quan hệ với bảng users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Hàm định dạng lại created_at trước khi trả về
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y H:i:s');
    }
}
