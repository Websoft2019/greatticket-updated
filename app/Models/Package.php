<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Package extends Model
{
    use HasFactory;
    protected $fillable = ['slug','event_id','title','cost', 'discount_price', 'actual_cost', 'photo','description', 'capacity', 'consumed_seat
    ', 'maxticket', 'status', 'seat_status'];

    /**
     * Get the event that owns the Package
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class,'package_id','id');
    }

    // public function orders()
    // {
    //     return $this->belongsToMany(Order::class, 'order_package');
    // }

    public function orderPackages(){
        return $this->hasMany(OrderPackage::class, 'package_id', 'id');
    }

    public function seats(){
        return $this->hasMany(Seat::class);
    }
}
