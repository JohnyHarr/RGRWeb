<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'phone',
        'name'
    ];

    public function place(){
        return $this->belongsTo(Place::class);
    }

}
