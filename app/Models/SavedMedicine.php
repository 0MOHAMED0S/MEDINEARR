<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedMedicine extends Model
{
    // تحديد اسم الجدول بشكل صريح كما طلبنا سابقاً
    protected $table = 'medicines_saved';

    protected $fillable = [
        'user_id',
        'medicine_id',
        'pharmacy_id',
    ];

    // ========================================================
    // ✨ العلاقات الجديدة المطلوبة لكي يعمل الـ API بنجاح ✨
    // ========================================================

    /**
     * علاقة الدواء (Medicine)
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    /**
     * علاقة الصيدلية (Pharmacy)
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    /**
     * علاقة المستخدم (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ========================================================

    public function savedByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'medicines_saved',
            'medicine_id',
            'user_id'
        );
    }
}
