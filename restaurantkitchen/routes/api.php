<?php

use App\Http\Controllers\IngredientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('ingredients/purchasehistory', [IngredientController::class, 'getPurchaseHistory']);
Route::apiResource('ingredients', IngredientController::class);

Route::post('ingredients/take', [IngredientController::class, 'take']);
Route::post('ingredients/enough', [IngredientController::class, 'enough']);
