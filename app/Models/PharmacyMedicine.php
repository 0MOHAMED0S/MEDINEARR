<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PharmacyMedicine extends Pivot
{
    protected $table = 'pharmacy_medicines';

    protected $fillable = [
        'pharmacy_id',
        'medicine_id',
        'price',
        'quantity',
        'status',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
    public function isAvailable()
    {
        return $this->status === 'available' && $this->quantity > 0;
    }
}
