<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
    ];
    public function medicines()
    {
        return $this->hasMany(Medicine::class, 'category_id');
    }

    // Accessor لجلب مسار الصورة بسهولة في الـ Blade
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        // مسار لصورة افتراضية في حال عدم رفع صورة
        return asset('Dashboard/images/logo.png');
    }
}
