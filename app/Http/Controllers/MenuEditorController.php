<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuEditorController extends Controller
{
    public function index($categoryId)
    {
        $category = MenuCategory::where('id', '=', $categoryId)->first();
        $menuItems = MenuItem::where('menu_category_id', '=', $categoryId)->orderBy('name', 'desc')->get();
        return view('adminMenuEditor', compact('menuItems', 'category'));
    }

    public function addNewCategory(Request $request)
    {
        $input = $request->all();
        $category = new MenuCategory;
        $category->category_name = $input['categoryName'];
        $category->save();
        return response()->json([
            'result' => 'added',
            'category_id' => $category->id,
            'category_name' => $category->category_name
        ]);
    }

    public function addNewDish(Request $request)
    {
        $input = $request->all();
        if ($request->hasFile('image')) {
            $imgPath = $request->file('image')->store('menuItemsImgs', 'public');
        } else {
            $imgPath = null;
        }
        $item = new MenuItem;
        $item->name = $input['name'];
        $item->menu_category_id = $input['category_id'];
        $item->description = $input['description'];
        $item->image = $imgPath;
        $item->price = $input['price'];
        $item->save();
        return response()->json([
            'result' => 'saved',
            'id' => $item->id,
            'name' => $item->name,
            'imgPath' => $imgPath,
            'description' => $item->description,
            'price' => $item->price
        ]);
    }

    public function updateMenuItem(Request $request)
    {
        $input = $request->all();
        if ($request->hasFile('image')) {
            $imgPath = $request->file('image')->store('menuItemsImgs', 'public');
            MenuItem::updateOrCreate([
                'id' => $input['id']
            ], [
                'name' => $input['name'],
                'description' => $input['description'],
                'image' => $imgPath,
                'price' => $input['price']
            ]);
        } else {
            MenuItem::updateOrCreate([
                'id' => $input['id']
            ], [
                'name' => $input['name'],
                'description' => $input['description'],
                'price' => $input['price']
            ]);
        }
        $imgPath = MenuItem::where('id', '=', $input['id'])->first()->image;
        return response()->json([
            'result' => 'saved',
            'id' => $input['id'],
            'name' => $input['name'],
            'imgPath' => $imgPath,
            'description' => $input['description'],
            'price' => $input['price']
        ]);
    }

    public function deleteDish($id)
    {
        MenuItem::where('id', '=', $id)->delete();
        return response('deleted');
    }

    public function editCategoryName(Request $request){
        $input = $request->all();
        MenuCategory::updateOrInsert([
            'id' => $input['category_id']
        ],[
           'category_name' => $input['name']
        ]);
        return response()->json([
            'result' => 'success',
            'id' => $input['category_id'],
            'name' => $input['name']
        ]);
    }

    public function categories(Request $request)
    {
        return response()->json([
                'categories' => MenuCategory::orderBy('category_name', 'desc')->get()->toArray()
            ]
        );
    }

}
