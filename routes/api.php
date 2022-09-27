<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\RequestClass;
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
Route::get('/createPost', [PostController::class, 'create']);
Route::get('/listClass', [ClassroomController::class, 'index']);
Route::put('/updatePost/{id}', [PostController::class, 'approvePost']);
Route::post('/createClassroom', [ClassroomController::class, 'create']);
Route::post('/createRequest', [UserController::class, 'createRequest']);
Route::post('/approveUser/{id}', [ClassroomController::class, 'approveUser']);
Route::get('/listUser/{id}', [ClassroomController::class, 'listUser']);