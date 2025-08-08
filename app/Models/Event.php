<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Casts\TimeCast;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['slug','title','date','time','vennue','primary_photo','seat_view','highlight','longitude','latitude','organizer_id','description', 'category_id', 'status'];
    protected $casts = [
        'longitude' => 'float',
        'latitude' => 'float',
        'organizer_id' => 'integer',
        'date' => 'date:Y-m-d'
    ];
   
    /**
     * Get the user that owns the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id', 'id');
    }

    /**
     * Get all of the images for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'event_id', 'id');
    }

    /**
     * Get all of the packages for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function packages(): HasMany
    {
        return $this->hasMany(Package::class, 'event_id', 'id');
    }

    public function cart(){
        return $this->hasOne(Event::class, 'event_id','id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function trendings(){
        return $this->hasMany(TrendingEvent::class);   
    }
}



