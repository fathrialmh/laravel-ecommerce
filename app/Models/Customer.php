<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\District;

class Customer extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    protected $guarded = [];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}


