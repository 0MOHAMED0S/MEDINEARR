<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PacketItem extends Model
{
    protected $fillable = [
        'packet_id',
        'type',
        'note',
        'image',
        'medicine_id'
    ];

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
