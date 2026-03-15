<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'title', 'description', 'image', 'link', 'bg_color', 'coupon_id', 'is_active'
    ];

    protected $appends = ['image_url'];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
