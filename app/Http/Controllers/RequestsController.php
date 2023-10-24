<?php

namespace App\Http\Controllers;

use App\Models\Banquet;
use App\Models\BookingPlace;
use Illuminate\Http\Request;

class RequestsController extends Controller
{
    public function index(){
        $bookingsPlaces = BookingPlace::all();
        $banquets = Banquet::all();
        return view('adminCheckAllRequests', compact('bookingsPlaces', 'banquets'));
    }

    public function delete(Request $request){
        $input = $request->all();
        BookingPlace::where('id', '=', $input['id'])->delete();
        return response('deleted');
    }

    public function deleteBanquet(Request $request){
        $input = $request->all();
        Banquet::where('id', '=', $input['id'])->delete();
        return response('deleted');
    }

}
