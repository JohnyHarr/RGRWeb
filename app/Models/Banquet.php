<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banquet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'date',
        'totalPrice',
        'amountOfPerson',
        'dishes'
    ];

    public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }

}
