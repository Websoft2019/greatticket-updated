<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'row_label',
        'seat_number',
        'position_x',
        'position_y',
        'status',
        'reserved_at',
        'expires_at',
    ];

    protected $casts = [
        'position_x' => 'integer',
        'position_y' => 'integer',
        'reserved_at' => 'timestamp',
        'expires_at' => 'timestamp'
    ];

    // Relationships
    public function package(){
        return $this->belongsTo(Package::class);
    }

    public function ticketUser(){
        return $this->hasOne(Seat::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_seat')->withTimestamps();
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeReserved($query)
    {
        return $query->where('status', 'reserved');
    }

    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    public function scopeIsExpired($query)
    {
        return $query->where('status', 'reserved')
                     ->where('expires_at', '<', now());
    }

}
