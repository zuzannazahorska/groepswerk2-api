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


//get all users
Route::get('/users' , function(){
    $users = DB::table('users')->get();
    return $users;
});

//get user per name
Route::get('/users/{name}', function($name){
    return DB::table('users')->where('name', $name)->first();
});

//add new user
Route::post('/users', function (Request $request){
    $name = $request -> input('name');
    $password = $request -> input('password');
    $email = $request -> input('email');

    if (DB::table('users')->where('name', $name)-> exists()){
        return response()->json([
            'message' => 'User already exists'
        ], 409);
    }

    DB::table('users')->insert([
        'name'=>$name,
        'password'=>Hash::make($password),
        'email'=>$email
    ]);

    return response()->json([
        'message'=>'User created.'
    ], 201);

});

//delete user
Route::delete('/users/{id}', function($id){
    DB::table('users')->where('id', $id)->delete();

    return response()->json([
        'message'=>'The user has been deleted'
    ], 200);
});

// GET ingredients 
Route::get ('/ingredients', function(){
    $ingredients = DB::table('ingredients')-> get();
    return $ingredients;
});

