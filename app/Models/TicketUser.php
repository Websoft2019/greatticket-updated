<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketUser extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "name",
        "gender",
        "qr_code",
        "qr_image",
        "checked_in",
        "order_package_id",
        "ic",
        "membership_no",
        "ticket_type",
        "seat_id",
    ];

    // public function religion(){
    //     return $this->belongsTo(Religion::class,"religion_id","id");
    // }

    public function orderPackage(){
        return $this->belongsTo(OrderPackage::class,"order_package_id","id");
    }

    public function seat(){
        return $this->belongsTo(Seat::class);
    }
}
