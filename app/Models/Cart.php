<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "event_id",
        "package_id",
        "quantity",
        "cost",
        "commision",
    ];

    protected $casts = [
        'event_id' => 'integer',
        'quantity' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function package()
    {
        return $this->belongsTo(Package::class, "package_id", "id");
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'cart_seat')->withTimestamps();
    }
}
