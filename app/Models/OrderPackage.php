<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPackage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "order_package";
    protected $fillable = ['order_id', 'package_id','quantity', 'is_complementary'];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function package(){
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function ticketUsers(){
        return $this->hasMany(TicketUser::class, 'order_package_id', 'id');
    }
}
