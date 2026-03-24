<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'official_price',   // <-- تم إضافته هنا
        'is_price_fixed',   // <-- تم إضافته هنا
        'category_id',
        'status',
    ];

    // تحويل أنواع البيانات لضمان عدم وجود أخطاء في الـ API
    protected $casts = [
        'official_price' => 'decimal:2',
        'is_price_fixed' => 'boolean',
        'status'         => 'boolean',
    ];

    // علاقة الدواء بالتصنيف
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // جلب مسار الصورة
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return asset('Dashboard/images/logo.png');
    }

    public function pharmacies()
    {
        return $this->belongsToMany(Pharmacy::class, 'pharmacy_medicines')
            ->withPivot('price', 'quantity', 'status');
    }
}
