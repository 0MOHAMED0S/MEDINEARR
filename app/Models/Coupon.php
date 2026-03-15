<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

protected $fillable = [
        'title', // الحقل الجديد
        'code',
        'discount_type',
        'discount_value',
        'usage_limit',
        'used_count',
        'expiry_date',
        'is_active',
    ];
    protected $casts = [
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];
}
