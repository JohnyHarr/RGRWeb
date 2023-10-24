<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantsInfoEditorController extends Controller
{
    public function index(){
        $restaurants = Restaurant::all();
        return view('adminRestaurants', compact('restaurants'));
    }

    public function createNewRestaurant(Request $request){
        $input = $request->all();
        $restaurant = new Restaurant;
        $restaurant->name = $input['name'];
        $restaurant->save();
        for ($i=0; $i<$input['places']; $i++){
            $place = new Place;
            $place->restaurant_id = $restaurant->id;
            $place->save();
        }
        return response()->json([
            'result' => 'saved',
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'amount' => $restaurant->places()->count()
        ]);
    }

    public function updateRestaurant(Request $request){
        $input = $request->all();
        Restaurant::updateOrInsert([
            'id' => $input['id']
        ],[
            'name' => $input['name']
        ]);
        Place::where('restaurant_id', '=', $input['id'])->delete();
        for ($i=0; $i<$input['places']; $i++){
            $place = new Place;
            $place->restaurant_id = $input['id'];
            $place->save();
        }
        $restaurant = Restaurant::where('id', '=', $input['id'])->first();
        return response()->json([
            'result' => 'saved',
            'id' => $input['id'],
            'name' => $input['name'],
            'amount' => $restaurant->places()->count()
        ]);
    }

    public function deleteRestaurant(Request $request){
        $input = $request->all();
        Restaurant::where('id', '=', $input['id'])->delete();
        return response('deleted');
    }

}
