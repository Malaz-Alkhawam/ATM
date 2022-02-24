<?php

use App\Http\Controllers\productController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|``
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


////user///
Route::middleware('auth:sanctum')->get('/logout', "UserController@logout");
Route::post('/signUp', "UserController@signUp");
Route::post('/login', "UserController@login");
Route::middleware('auth:sanctum')->put('editUserInfo', 'UserController@editUserInfo');
Route::middleware('auth:sanctum')->delete('/deleteUser', 'UserController@deleteUserByEmail');

////products///
Route::post('/image', "ProductController@image");
Route::post('/addProduct', "ProductController@addProduct");
Route::middleware('auth:sanctum')->get('/getAllProducts', 'ProductController@getAllProducts');
Route::middleware('auth:sanctum')->post('/getProductByCategory', 'ProductController@getProductByCategory');
Route::middleware('auth:sanctum')->get('/getMyProducts', 'ProductController@getMyProducts');
Route::middleware('auth:sanctum')->get('/getProductByUser', 'ProductController@getProductByUser');
Route::middleware('auth:sanctum')->delete('/deleteProductbyId', 'ProductController@deleteProductbyId');
Route::middleware('auth:sanctum')->post('/editProduct', 'ProductController@editProduct');
Route::middleware('auth:sanctum')->post('/getProductsByName', 'ProductController@getProductsByName');

////likes///
Route::middleware('auth:sanctum')->post('/addLike', 'LikeController@addLikeOrDeleteLike');

////comments///
Route::middleware('auth:sanctum')->post('/addComment', 'CommentController@addComment');
Route::middleware('auth:sanctum')->put('/editComment', 'CommentController@editComment');
Route::middleware('auth:sanctum')->delete('/deleteComment', 'CommentController@deleteComment');

////categories///
Route::middleware('auth:sanctum')->post('/addCategory', "CategController@addCategory");
Route::middleware('auth:sanctum')->get('/getAllCategories', "CategController@getAllCategories");
Route::middleware('auth:sanctum')->post('/editCategory', "CategController@editCategory");
Route::middleware('auth:sanctum')->delete('/deleteCategory', "CategController@deleteCategory");

////seens///
Route::middleware('auth:sanctum')->post('/addSeen', "SeenController@addSeen");
