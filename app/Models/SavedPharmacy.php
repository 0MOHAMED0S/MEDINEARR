<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedPharmacy extends Model
{
    protected $table = 'pharmacies_saved';

    protected $fillable = [
        'user_id',
        'pharmacy_id',
    ];

    public function savedByUsers()
    {
        return $this->belongsToMany(
            User::class, 
            'pharmacies_saved',
            'pharmacy_id',
            'user_id'
        );
    }
}
