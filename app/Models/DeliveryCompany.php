<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DeliveryCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'status',
    ];

    // Accessor لجلب مسار الصورة بشكل نظيف أو عرض صورة افتراضية
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Str::startsWith($this->image, ['http://', 'https://'])
                ? $this->image
                : asset('storage/' . $this->image);
        }

        // صورة افتراضية باستخدام الحرف الأول من اسم الشركة
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=f8fafc&color=334155';
    }
}
