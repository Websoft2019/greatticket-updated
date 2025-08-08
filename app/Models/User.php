<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'session_id',
        'email',
        'password',
        'address',
        'country',
        'state',
        'city',
        'postcode',
        'otp',
        'otp_expires_at',
        'role',
        'gender',
        'religion_id',
        'contact',
        'icnumber',
        'dob',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
    ];

    /**
     * Always encrypt the password when it is updated.
     *
     * @param $value
    * @return string
    */
    public function setPasswordAttribute($value)
    {
        // $this->attributes['password'] = Hash::make($value);
        $this->attributes['password'] = $value;
    }

    /**
     * Get all of the events for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'organizer_id', 'id');
    }

    public function carts(){
        return $this->hasMany(Cart::class, 'user_id','id');
    }

    public function orders(): HasMany{
        return $this->hasMany(Order::class,'user_id','id');
    }

    public function organizer(): HasOne{
        return $this->hasOne(Organizer::class,'user_id','id');
    }

    public function religion(){
        return $this->belongsTo(Religion::class,'religion_id','id');
    }
}
