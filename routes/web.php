<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use \App\User;
use \App\Product;
 
 
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard', 'HomeController@index')->name('dashboard'); 
Route::get('/about', 'AboutController@index'); 
Auth::routes();  


// ------------- PRODUCT ROUTE -----------------//


Route::get('/products', 'ProductsController@index'); 
  
Route::get('/products/add', 'ProductsController@index');

Route::get('/products/{id}/edit', 'ProductsController@index');

Route::get('/products/{id}', 'ProductsController@index');

Route::get('/pipe/products/search',function(){
    $p = Input::get('q'); 
    $product = Product::where('name','like','%'.$p.'%')->take(5)->get();
    return response()->json($product);
}); 

// List single post
Route::get('/pipe/products/records', 'ProductsController@records');
  
// view post
Route::get('/pipe/products/{id}', 'ProductsController@record');

//add new post
Route::post('/pipe/products', 'ProductsController@store');

//update
Route::put('/pipe/products', 'ProductsController@store');
  
// Delete post
Route::delete('/pipe/products/{id}', 'ProductsController@destroy'); 
// ------------- END OF PRODUCT ROUTE -----------------//



// ------------- TRANSACTION ROUTE -----------------//
// TransactionsController 
Route::get('/transactions', 'TransactionsController@index');
Route::get('/transactions/{id}', 'TransactionsController@index');

Route::get('/pipe/transactions', 'TransactionsController@records');
Route::get('/pipe/transactions/{id}', 'TransactionsController@record');
Route::post('/pipe/transactions', 'TransactionsController@store');
// ------------- END OF TRANSACTION ROUTE -----------------//


// ------------- TRANSACTION ROUTE -----------------//
// InventoryController 
Route::get('/inventory', 'InventoryController@index');
// ------------- END OF TRANSACTION ROUTE -----------------//

 
// ------------- POST ROUTE -----------------//
Route::get('/posts', 'PostsController@index'); 
  
Route::get('/posts/add', 'PostsController@index');

Route::get('/posts/{id}/edit', 'PostsController@index');

Route::get('/posts/{id}', 'PostsController@index');

// List single post
Route::get('/pipe/posts/records', 'PostsController@records');
  
// view post
Route::get('/pipe/posts/{id}', 'PostsController@record');

//add new post
Route::post('/pipe/posts', 'PostsController@store');

//update
Route::put('/pipe/posts', 'PostsController@store');
  
// Delete post
Route::delete('/pipe/posts/{id}', 'PostsController@destroy');
// ------------- END OF POST ROUTE -----------------// 

   
// ------------- MEMBERSHIP ROUTE -----------------//
Route::get('/members', 'MembersController@index');   
Route::get('/members/{id}', 'MembersController@index');   
Route::get('/members/{id}/edit', 'MembersController@index');  
Route::get('membership', 'MembersController@membershipTree');  
Route::get('/pipe/members/getDownlineMembers/{id}', 'MembersController@getDownlineMembers');
Route::get('/pipe/members', 'MembersController@records');
Route::get('/pipe/members/{id}', 'MembersController@record');
Route::post('/pipe/members', 'MembersController@store');
Route::put('/pipe/members', 'MembersController@store');
Route::delete('/pipe/members/{id}', 'MembersController@destroy');

Route::get('/pipe/user/search',function(){
    $m = Input::get('q');
    $users = User::where('name','like','%'.$m.'%')->take(5)->get();
    return response()->json($users);
   }); 
// ------------- END OF MEMBERSHIP ROUTE -----------------// 


// ------------- Inventory ROUTE -----------------//
Route::post('/pipe/inventory/addStock', 'InventoryController@store');

// ------------- END OF Inventory ROUTE -----------------// 

// Route::get('/statuses', function(){
//     return App\Status::with('user')->latest()->get();
// });

//Route::get('/statuses', 'StatusesController@index');
//Route::post('/statuses', 'StatusesController@store');



//Route::get('/home', 'HomeController@index')->name('home');


