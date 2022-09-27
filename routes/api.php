<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\PostController;
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
Route::post('/createPost', [PostController::class, 'create']);
Route::get('/listPost', [PostController::class, 'index']);
Route::get('/listClass', [ClassroomController::class, 'index']);
Route::put('/updatePost/{id}', [PostController::class, 'approvePost']);