<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pharmacy_application_id',
        'pharmacy_name',
        'owner_name',
        'phone',
        'email',
        'city',
        'address',
        'working_hours',
        'license_number',
        'image',
        'license_document',
        'lat',
        'lng',
        'services',
        'has_collaboration',
        'is_active',
        'is_big_pharmacy'
    ];

    protected $casts = [
        'services'          => 'array',
        'has_collaboration' => 'boolean',
        'is_active'         => 'boolean',
        'is_big_pharmacy'   => 'boolean',
        'lat'               => 'decimal:8',
        'lng'               => 'decimal:8',
    ];

    // Relationship to the User (Owner)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to the Application
    public function application()
    {
        return $this->belongsTo(PharmacyApplication::class, 'pharmacy_application_id');
    }

    public function inventory()
    {
        return $this->hasMany(PharmacyMedicine::class);
    }

    // لجلب الأدوية مباشرة من جدول الأدوية عبر الوسيط
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'pharmacy_medicines')
            ->withPivot('id', 'price', 'quantity', 'status')
            ->withTimestamps();
    }
}
