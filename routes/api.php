<?php

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
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/users/{name}', function($name){
    return DB::table('users')->where('name', $name)->first();
    
});

Route::get('/recipes_ingredients', function(){
    return DB::table('recipes_ingredients');
});


Route::get('/recipes_ingredients/{id}', function($id){
    return DB::table('recipes_ingredients')->where('id', $id)->first();

});


