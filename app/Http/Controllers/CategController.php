<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategController extends Controller
{
    public function addCategory(Request $request)
    {
        $category = new Category();
        if (!$request->file('image')->isValid()) {
            return response()->json(["message" => "image not valid"], 400);
        }

        $imagePath = public_path() . "\\categoryImages";
        $request->file('image')->move($imagePath, time() . "." . $request->file('image')->getClientOriginalExtension()); // request body file(image)
        $imageName = time() . "." . $request->file('image')->getClientOriginalExtension();

        $category->name = $request->input('name');
        $category->image = $imageName;

        $category->save();
        return response()->json(["message" => "added successfuly"]);
    }

    public function getAllCategories(Request $request)
    {
        $categories = Category::get();
        for ($i = 0; $i < sizeof($categories); $i++) {
            $categories[$i]['image'] = 'http://' . $request->getHttpHost() . '/categoryImages' . '/' . $categories[$i]['image'];
        }
        return response()->json($categories);
    }

    public function editCategory(Request $request)
    {

        $category = Category::find($request->input("category_id"));
        if ($category == null)
            return response()->json(['message' => 'bad request'], 400);
        if (!$request->file('image')->isValid()) {
            return response()->json(["message" => "image not valid"], 400);
        }

        File::delete(public_path() . "\\categoryImages\\" . $category['image']);

        $imagePath = public_path() . "\\categoryImages";
        $request->file('image')->move($imagePath, time() . "." . $request->file('image')->getClientOriginalExtension()); // request body file(image)
        $imageName = time() . "." . $request->file('image')->getClientOriginalExtension();

        $category->name = $request->input('name');
        $category->image = $imageName;

        $category->save();
        return response()->json(["message" => "edited successfuly"]);
    }

    public function deleteCategory(Request $request)
    {

        $category = Category::find($request->input("category_id"));
        if ($category == null)
            return response()->json(['message' => 'bad request'], 400);
        File::delete(public_path() . "\\categoryImages\\" . $category['image']);
        Category::destroy($category->id);
        return response()->json(["message" => "deleted successfuly"]);
    }
}
