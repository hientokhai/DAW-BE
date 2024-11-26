<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'site_infos';

    protected $fillable = [
        'shop_name',
        'address',
        'phone_number',
        'email',
        'description',
        'website',
        'business_area',
        'policies',
        'logo_header_url',
        'logo_footer_url',
        'social_facebook',
        'social_instagram',
        'social_twitter',
        'social_linkedin',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
//