<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyWallet extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function pharmacy() { return $this->belongsTo(Pharmacy::class); }
}
