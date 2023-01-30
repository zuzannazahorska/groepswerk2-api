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

// get recipes based on diet
Route::get('/diet_recipe/{name}', function ($name) {
    $data = DB::table('diet_recipe')
        ->join('diets', 'diets.id', '=', 'diet_recipe.diet_id')
        ->join('recipes', 'recipes.id', '=', 'diet_recipe.recipe_id')
        ->where('diets.name', 'like', '%'.$name.'%')
        ->select('diets.name', 'diets.id', 'recipes.name', 'recipes.instruction')
        ->get();
    return $data;
});

//get recipes based on ingredient
Route::get('/ingredient_recipe/{name}', function ($name) {
    $data = DB::table('ingredient_recipe')
        ->join('ingredients', 'ingredients.id', '=', 'ingredient_recipe.ingredient_id')
        ->join('recipes', 'recipes.id', '=', 'ingredient_recipe.recipe_id')
        ->where('ingredients.name', 'like', '%'.$name.'%')
        ->select('recipes.name', 'recipes.id', 'recipes.instruction', 'ingredients.name')
        ->get();
    return $data;
});





//delete user
Route::delete('/users/{id}', function($id){
    DB::table('users')->where('id', $id)->delete();
    return response()->json([
        'message'=>'The user has been deleted'
    ], 200);
});


// GET all ingredients 
Route::get ('/ingredients', function(){
    $ingredients = DB::table('ingredients')-> get();
    return $ingredients;
 });   
 
//get ingredient per ingredient 
Route::get('/ingredients/{name}', function($name){
    return DB::table('ingredients')->where('name', $name)->get();
});



Route::get('/recipes', function () {
   return DB::table('recipes')->get();
});


Route::get('/recipes/instructions/{id}', function ($id) {
    $data = DB::table('recipes')
    ->where('recipes.id', '=', $id)
       ->select('recipes.instruction', 'recipes.name', 'recipes.image')
        ->get();
    return $data;

});

//get a user based on email
Route::get('users/emails/{email}', function($email){
    return DB::table('users')->where('email', $email)->first();
});


//get recipes based on all ingredients of frige

Route::get('/ir/search', function (Request $request) {
    $search = $request->input('search');
    $searchArray = explode (' ', $search);
    $query = DB::table ('ingredient_recipe')
        ->join('ingredients', 'ingredients.id', '=', 'ingredient_recipe.ingredient_id')
        ->join('recipes', 'recipes.id', '=', 'ingredient_recipe.recipe_id')
        ->select ('ingredients.id','recipes.id','recipes.name','recipes.instruction'); 
        foreach ($searchArray as $searchWord) {
          $query = $query->orWhere('ingredients.name','like','%'.$searchWord.'%');
        }
     return $query 
        ->orderBy ('recipes.id','desc')
        ->get();
 });
 
//get ingredients based on type list and user id
Route::get('/ingredient_user/{user_id}/{list}', function($userId, $listType){
    $ingredients = DB::table('ingredient_user')
        ->join('ingredients', 'ingredient_user.ingredient_id', '=', 'ingredients.id' )
        ->where ('ingredient_user.user_id', $userId)
        ->where('ingredient_user.list', $listType)
        ->select('ingredients.id', 'ingredients.name')
        ->get();

    return $ingredients;
});


 //get ingredients_user
 Route::get('/ingredient_user', function(){
    return DB::table('ingredient_user')->get();
 });


 //post to ingredients_user
 Route::post('/ingredient_user', function (Request $request){
    $user_id = $request -> input('user_id');
    $ingredient_id = $request -> input('ingredient_id');
    $list = $request -> input('list');


    DB::table('ingredient_user')->insert([
        'user_id'=>$user_id,
        'ingredient_id'=>$ingredient_id,
        'list'=>$list
    ]);

    return response()->json([
        'message'=>'data added'
    ], 201);
});

//delete from ingredient_user based on the ingredient's id
Route::delete('/ingredient_user/{user_id}/{ingredient_id}', function($user_id, $ingredient_id){
    DB::table('ingredient_user')-> where ('user_id', $user_id)->where('ingredient_id', $ingredient_id) ->delete();
    return response()->json([
        'message'=>'The ingredient has been deleted'
    ], 200);
});