<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packet extends Model
{
protected $fillable = ['user_id', 'title', 'description'];

    // الباكيت يمتلك عناصر متعددة
    public function items()
    {
        return $this->hasMany(PacketItem::class);
    }
}
