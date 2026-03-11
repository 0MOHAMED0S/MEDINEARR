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
        'category_id',
        'status',
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
}
