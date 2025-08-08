<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['code', 'cost', 'coupontype', 'couponlimitation', 'organizer_id', 'expire_at'];

    public function orders()
    {
        return $this->hasMany(Order::class, "coupon_id", "id");
    }
}
