<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PacketItem extends Model
{
    protected $fillable = ['packet_id', 'type', 'note', 'image', 'medicine_id', 'meta'];

    protected $casts = [
        'meta' => 'array', // تحويل الـ JSON تلقائياً إلى مصفوفة
    ];

    // ارتباط العنصر بالباكيت الأساسي
    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    // ارتباط العنصر بالدواء (في حال كان النوع medicine)
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // ✨ Accessor ذكي لإرجاع الرابط الكامل للصورة تلقائياً
    public function getImageUrlAttribute()
    {
        if ($this->type === 'image' && $this->image) {
            return str_starts_with($this->image, 'http') ? $this->image : asset('storage/' . $this->image);
        }
        return null;
    }
}
