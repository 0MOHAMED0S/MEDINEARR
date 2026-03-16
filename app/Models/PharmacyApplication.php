<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyApplication extends Model
{
    protected $fillable = [
        'user_id',
        'pharmacy_name',
        'owner_name',
        'phone',
        'email',
        'city',
        'address',
        'lat',
        'lng',
        'working_hours',
        'license_number',
        'license_document',
        'services',
        'has_collaboration',
        'image',
        'status',
        
    ];

    protected $casts = [
        'services' => 'array',
        'has_collaboration' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
