<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrendingEvent extends Model
{
    use HasFactory;
    protected $fillable = ['event_id', 'priority'];

    public function event(){
        return $this->belongsTo(Event::class);
    }
}
