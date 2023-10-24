<?php

namespace App\Http\Controllers;

use App\Models\BookingPlace;
use App\Models\Place;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;

class OrderPlaceController extends Controller
{
    public function index(){
        $restaurants = Restaurant::all();
        return view('orderPlace', compact('restaurants'));
    }

    public function getPlaces($id){
        $places = Place::where('restaurant_id', '=', $id)->get()->toArray();
        return response()->json([
            'places' => $places
        ]);
    }

    public function order(Request $request){
        $input = $request->all();
        $newBooking = new BookingPlace;
        $newBooking->name = $input['name'];
        $newBooking->date = $input['date'];
        $newBooking->phone = $input['phone'];
        $newBooking->place_id = $input['place'];
        $newBooking->save();
        return response()->json([
            'result' => 'added'
        ]);
    }

    public function checkTimeSpace($date){
        $booking = BookingPlace::where('date', $date)->first();
        if($booking){
            return response('not_available');
        } else {
            return response('available');
        }
    }

}
