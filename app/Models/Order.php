<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    // إنشاء رقم مرجعي أوتوماتيكي قبل حفظ الطلب لأول مرة
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_reference = 'MED-' . strtoupper(uniqid());
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function pharmacy() { return $this->belongsTo(Pharmacy::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function coupon() { return $this->belongsTo(Coupon::class); }
}
