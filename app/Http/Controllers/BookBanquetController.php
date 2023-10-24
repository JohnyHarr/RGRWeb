<?php

namespace App\Http\Controllers;

use App\Models\Banquet;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class BookBanquetController extends Controller
{

    public function index(){
        $restaurants = Restaurant::all();
        $menuCategories = MenuCategory::all();
        return view('bookBanquet', compact('restaurants', 'menuCategories'));
    }

    public function book(Request $request){
        $input = $request->all();
        $banquet = new Banquet;
        $banquet->name = $input['name'];
        $banquet->phone = $input['phone'];
        $banquet->date = $input['date'];
        $banquet->amountOfPerson = $input['amountOfPerson'];
        $banquet->dishes = json_encode($input['dish']);
        $banquet->totalPrice = $this->totalPrice($input['dish'], $input['amountOfPerson'])->getContent();
        $banquet->restaurant_id = $input['restaurant'];
        $banquet->save();
        return response()->json([
            'result' => 'added'
        ]);
    }

    public function getTotalPrice(Request $request){
        $input =json_decode($request->getContent(), true);
        return response()->json([
            'result' => 'success',
            'totalPrice' => $this->totalPrice($input['menuItems'], $input['amount'])
        ]);
    }

    private function totalPrice($menuItems, $amountOfPerson){
        $totalPrice = 0;
        foreach ($menuItems as $item){
            $totalPrice+=MenuItem::where('id', '=', $item)->first()->price*$amountOfPerson;
        }
        return response($totalPrice);
    }

}
