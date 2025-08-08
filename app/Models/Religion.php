<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Religion extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasManyy(User::class, "religion_id", "id");
    }

    public function ticketUsers(){
        return $this->hasMany(TicketUser::class,"religion_id","id");
    }
}
