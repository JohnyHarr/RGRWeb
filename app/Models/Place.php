<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    public function bookings()
    {
        return $this->hasMany(BookingPlace::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
