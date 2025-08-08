<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'photo', 'about', 'address', 'cm_type', 'cm_value', 'verify'];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
