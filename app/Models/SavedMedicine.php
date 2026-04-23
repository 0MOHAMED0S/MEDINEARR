<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedMedicine extends Model
{
    protected $table = 'medicines_saved';

    protected $fillable = [
        'user_id',
        'medicine_id',
        'pharmacy_id',
    ];

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
