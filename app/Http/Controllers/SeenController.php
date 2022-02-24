<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seen;
use Illuminate\Http\Request;

class SeenController extends Controller
{
    public function addSeen(Request $request)
    {
        $seen = new Seen();
        if (Product::find($request->input('product_id')) == null)
            return response()->json(['message' => 'bad request'], 400);
        $seen->product_id = $request->input('product_id');
        $seen->user_id = $request->User()['id'];
        $seen->save();
        return response()->json(['message' => 'added successfuly']);
    }
}
