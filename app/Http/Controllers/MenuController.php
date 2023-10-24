<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(){
        $categories = MenuCategory::all();
        return view('menu', compact('categories'));
    }
}
