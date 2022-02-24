<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PriceRange;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class productController extends Controller
{


    public function addProduct(Request $request)
    {
        $product = new Product(); // database table

        if (!$request->has('json')) {
            return response()->json(["message" => "json not valid"], 400);
        }
        if ($request->file('image') == null)
            return response()->json(["message" => "image is required"], 400);
        if (!$request->file('image')->isValid()) {
            return response()->json(["message" => "image not valid"], 400);
        }
        $jsonProdect = json_decode($request->get('json'), true); // request body json
        $request->input('');

        $imagePath = public_path() . "\\productImages";
        $request->file('image')->move($imagePath, time() . "." . $request->file('image')->getClientOriginalExtension()); // request body file(image)
        $imageName = time() . "." . $request->file('image')->getClientOriginalExtension();

        $product->name = $jsonProdect['name'];
        $product->image = "$imageName";
        $product->price = $jsonProdect['price'];
        $product->quantity = $jsonProdect['quantity'];
        $product->callication = $jsonProdect['callication']; //communicationInfo
        $product->end_date = $jsonProdect['end_date'];
        if (Category::find($jsonProdect['category_id']) == null)
            return response()->json(['message' => 'category id not exist'], 400);
        $product->category_id = $jsonProdect['category_id'];
        $product->user_id = $request->user()['id'];
        $product->deleted = false;

        $product->save();

        $prices1 = new PriceRange();
        $prices2 = new PriceRange();
        $prices3 = new PriceRange();

        $prices1->product_id = $product->id;
        $prices1->deleted = false;
        $prices1->price = $jsonProdect['price1'];
        $prices1->price_offer =  $jsonProdect['price_offer1'];
        $prices1->date_offer = Carbon::now(); //////
        $prices1->save();

        $prices2->product_id = $product->id;
        $prices2->deleted = false;
        $prices2->price = $jsonProdect['price2'];
        $prices2->price_offer =  $jsonProdect['price_offer2'];
        $prices2->date_offer = Carbon::now(); //////
        $prices2->save();

        $prices3->product_id = $product->id;
        $prices3->deleted = false;
        $prices3->price = $jsonProdect['price3'];
        $prices3->price_offer =  $jsonProdect['price_offer3'];
        $prices3->date_offer = Carbon::now(); //////
        $prices3->save();


        return response()->json(["message" => "added successfuly"]);
    }




    public function getAllProducts(Request $request)
    {
        $products = Product::where('deleted', false)->get();
        $response = [];
        for ($i = 0; $i < sizeof($products); $i++) {

            $names = [];
            for ($j = 0; $j < sizeof($products[$i]->likes); $j++) {
                $names[] = $products[$i]->likes[$j]->User->name;
            }

            $response[] = [
                "id" => $products[$i]['id'],
                "name" => $products[$i]['name'],
                "image" => 'http://' . $request->getHttpHost() . '/productImages' . '/' . $products[$i]['image'],
                "category" => $products[$i]->category,
                "quantity" => $products[$i]['quantity'],
                "end_date" => $products[$i]['end_date'],
                "callication" => $products[$i]['callication'],
                "user" => $products[$i]->user['name'],
                "created_at" => $products[$i]['created_at'],
                "price" => $products[$i]->prices[0]['price'],
                "price_offer" => $products[$i]->prices[0]['price_offer'],
                "likes" => $names,
                "comments" => $products[$i]->comments,
                "seen" => $products[$i]->seens,
            ];
        }

        return response()->json($response);
    }

    public function getProductByCategory(Request $request)
    {

        if ($request->input('category_id') == null || $request->input('category_id') == '') {
            return response()->json([
                "message" => "Category id is required"
            ], 400);
        }
        $products = Product::where('deleted', false)->where('category_id', $request->input('category_id'))->get();
        $response = [];
        for ($i = 0; $i < sizeof($products); $i++) {
            $names = [];
            for ($j = 0; $j < sizeof($products[$i]->likes); $j++) {
                $names[] = $products[$i]->likes[$j]->User->name;
            }

            $comments = [];
            for ($j = 0; $j < sizeof($products[$i]->comments); $j++) {
                $comments[$j]['name'] = $products[$i]->comments[$j]->User->name;
                $comments[$j]['comment'] = $products[$i]->comments[$j]['comment'];
            }


            $response[] = [
                "id" => $products[$i]['id'],
                "name" => $products[$i]['name'],
                "image" => 'http://' . $request->getHttpHost() . '/productImages' . '/' . $products[$i]['image'],
                "category" => $products[$i]->category,
                "quantity" => $products[$i]['quantity'],
                "end_date" => $products[$i]['end_date'],
                "callication" => $products[$i]['callication'],
                "user" => $products[$i]->user['name'],
                "created_at" => $products[$i]['created_at'],
                "price" => $products[$i]->prices[0]['price'],
                "price_offer" => $products[$i]->prices[0]['price_offer'],
                "likes" => $names,
                "comments" => $products[$i]->comments,
                "seen" => $products[$i]->seens,
            ];
        }

        return response()->json($response);
    }


    public function getMyProducts(Request $request)
    {
        $products = Product::where('user_id', $request->User()['id'])->get();

        if ($products == null)
            return response()->json(['message' => 'no products']);
        $response = [];
        for ($i = 0; $i < sizeof($products); $i++) {

            if ($products[$i]->likes != null) {
                $names = [];
                for ($j = 0; $j < sizeof($products[$i]->likes); $j++) {
                    $names[] = $products[$i]->likes[$j]->User->name;
                }
            }
            if ($products[$i]->comments != null) {
                $comments = [];
                for ($j = 0; $j < sizeof($products[$i]->comments); $j++) {
                    $comments[$j]['name'] = $products[$i]->comments[$j]->User->name;
                    $comments[$j]['comment'] = $products[$i]->comments[$j]['comment'];
                }
            }

            $response[] = [
                "id" => $products[$i]['id'],
                "name" => $products[$i]['name'],
                "image" => 'http://' . $request->getHttpHost() . '/productImages' . '/' . $products[$i]['image'],
                "category" => $products[$i]->category,
                "quantity" => $products[$i]['quantity'],
                "end_date" => $products[$i]['end_date'],
                "callication" => $products[$i]['callication'],
                "user" => $products[$i]->user['name'],
                "created_at" => $products[$i]['created_at'],
                "price" => $products[$i]->prices[0]['price'],
                "price_offer" => $products[$i]->prices[0]['price_offer'],
                "likes" => $names,
                "comments" => $products[$i]->comments,
                "seen" => $products[$i]->seens,
            ];
        }

        return response()->json($response);
    }



    public function editProduct(Request $request)
    {
        $product = Product::find(json_decode($request->get('json'), true)['product_id']);
        if (!$request->has('json')) {
            return response()->json(["message" => "json not valid"], 400);
        }
        $imagePath = public_path() . "\\productImages";
        $request->file('image')->move($imagePath, time() . "." . $request->file('image')->getClientOriginalExtension()); // request body file(image)
        $imageName = time() . "." . $request->file('image')->getClientOriginalExtension();
        if ($request->file('image')->isValid()) {
            $product->image = "$imageName";
        }
        $jsonProdect = json_decode($request->get('json'), true); // request body json

        File::delete(public_path() . "\\productImages\\" . $product['image']);
        $product->name = $jsonProdect['name'];
        $product->image = $imageName;
        $product->price = $jsonProdect['price'];
        $product->quantity = $jsonProdect['quantity'];
        $product->callication = $jsonProdect['callication']; //communicationInfo
        $product->category_id = $jsonProdect['category_id'];
        $product->user_id = $request->user()['id'];
        $product->deleted = false;

        $product->save();


        return response()->json(["message" => "edited successfuly"]);
    }

    public function deleteProductbyId(Request $request)

    {

        $product = Product::find($request->input('product_id'));
        if ($product == null) {
            return response()->json(["messsage" => "id not valid"], 400);
        }
        if ($product->user['id'] != $request->User()['id'])
            return response()->json(["message" => "connot delete this product"], 400);
        $product->deleted = true;
        $product->save();
        return response()->json(["messsage" => "Deleted successfuly"]);
    }

    public function getProductsByName(Request $request)
    {
        $products = Product::where('name', $request->input('product_name'))->get();
        if ($products == null)
            return response()->json(['message' => 'name not exist'], 400);
        $response = [];
        for ($i = 0; $i < sizeof($products); $i++) {

            if ($products[$i]->likes != null) {
                $names = [];
                for ($j = 0; $j < sizeof($products[$i]->likes); $j++) {
                    $names[] = $products[$i]->likes[$j]->User->name;
                }
            }
            if ($products[$i]->comments != null) {
                $comments = [];
                for ($j = 0; $j < sizeof($products[$i]->comments); $j++) {
                    $comments[$j]['name'] = $products[$i]->comments[$j]->User->name;
                    $comments[$j]['comment'] = $products[$i]->comments[$j]['comment'];
                }
            }

            $response[] = [
                "id" => $products[$i]['id'],
                "name" => $products[$i]['name'],
                "image" => 'http://' . $request->getHttpHost() . '/productImages' . '/' . $products[$i]['image'],
                "category" => $products[$i]->category,
                "quantity" => $products[$i]['quantity'],
                "end_date" => $products[$i]['end_date'],
                "callication" => $products[$i]['callication'],
                "user" => $products[$i]->user['name'],
                "created_at" => $products[$i]['created_at'],
                "price" => $products[$i]->prices[0]['price'],
                "price_offer" => $products[$i]->prices[0]['price_offer'],
                "likes" => $names,
                "comments" => $products[$i]->comments,
                "seen" => $products[$i]->seens,
            ];
        }

        return response()->json($response);
    }
}
