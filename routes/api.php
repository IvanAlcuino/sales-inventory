<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// // ------------- PRODUCT ROUTE -----------------//
// // List single post
// Route::get('/products/records', 'ProductsController@records');
  
// // view post
// Route::get('/products/{id}', 'ProductsController@record');

// //add new post
// Route::post('/products', 'ProductsController@store');

// //update
// Route::put('/products', 'ProductsController@store');
  
// // Delete post
// Route::delete('/products/{id}', 'ProductsController@destroy');
// // ------------- END OF PRODUCT ROUTE -----------------//


// // ------------- MEMBERSHIP ROUTE -----------------//
// Route::get('/members/getDownlineMembers/{id}', 'MembersController@getDownlineMembers');
// Route::get('/members/{id}', 'MembersController@record'); 
// // ------------- END OF MEMBERSHIP ROUTE -----------------// 


// // ------------- POST ROUTE -----------------//
// // List single post
// Route::get('/posts/records', 'PostsController@records');
  
// // view post
// Route::get('/posts/{id}', 'PostsController@record');

// //add new post
// Route::post('/posts', 'PostsController@store');

// //update
// Route::put('/posts', 'PostsController@store');
  
// // Delete post
// Route::delete('/posts/{id}', 'PostsController@destroy');
// // ------------- END OF POST ROUTE -----------------// 