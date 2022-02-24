<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function addLikeOrDeleteLike(Request $request)
    {
        $like = Like::where('product_id', $request->input('product_id'))->where('user_id', $request->User()['id'])->get()->first();
        if ($like != null) {
            Like::destroy($like->id);
            return response()->json(['message' => 'deleted successfuly']);
        }
        $like = new Like();
        if (Product::find($request->input('product_id')) == null)
            return response()->json(['message' => 'bad request'], 400);
        $like->user_id = $request->User()['id'];
        $like->product_id = $request->input('product_id');
        $like->save();
        return response()->json(['message' => 'added successfuly']);
    }
}
