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



////////////////// RECIPES //////////////////////////////////////////////////////

//    - get recipe in depending on the type of diet


Route::get('/diet_recipe/{id}', function ($id) {
    $data = DB::table('diet_recipe')
        ->join('diets', 'diets.id', '=', 'diet_recipe.diet_id')
        ->join('recipes', 'recipes.id', '=', 'diet_recipe.recipe_id')
        ->where('diets.id', '=', $id)
        ->select('diets.name', 'diets.id', 'recipes.name', 'recipes.instruction')
        ->get();
    return $data;
});
  // get all recipes

Route::get('/recipes', function () {
    return DB::table('recipes')->get();
    });



