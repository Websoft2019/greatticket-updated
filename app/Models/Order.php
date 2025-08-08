<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "user_id",
        "name",
        "address",
        "email",
        "phone",
        "country",
        "state",
        "city",
        "postcode",
        "carttotalamount",
        "servicecharge",
        "discount_amount",
        "coupon_id",
        "grandtotal",
        "paymentmethod",
        "paymentstatus",
        "payer_id",
        "qr_code",
        "qr_image",
        "reserved_at",
        "expires_at",
    ];
    
    protected $casts = [
        'reserved_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // public function packages()
    // {
    //     return $this->belongsToMany(Package::class, 'order_package');
    // }

    public function orderPackages(){
        return $this->hasMany(OrderPackage::class, 'order_id', 'id');
    }

    public function coupon(){
        return $this->belongsTo(Coupon::class,'coupon_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
