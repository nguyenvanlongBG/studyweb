<?php

use App\Http\Controllers\ChooseQuestionController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CorrectAnswerController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QuestionTestController;
use App\Http\Controllers\QuestionNormalController;
use App\Http\Controllers\AnswerNormalController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Models\CorrectAnswer;
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
Route::get('/createPost/{id}', [PostController::class, 'create']);
Route::get('/listClass', [ClassroomController::class, 'index']);
Route::put('/updatePost/{id}', [PostController::class, 'approvePost']);
Route::post('/createClassroom', [ClassroomController::class, 'create']);
Route::post('/createRequest', [UserController::class, 'createRequest']);
Route::post('/approveUser/{id}', [ClassroomController::class, 'approveUser']);
Route::get('/listUser/{id}', [ClassroomController::class, 'listUser']);
Route::post('/createTest', [TestController::class, 'create']);
Route::get('/showTest', [TestController::class, 'show']);
Route::post('/createCorrectAnswer', [CorrectAnswerController::class, 'create']);
Route::post('/createQuestionTest', [QuestionTestController::class, 'create']);
Route::get('/test/questions', [QuestionTestController::class, 'listByIdTest']);
Route::post('/createQuestionNormal', [QuestionNormalController::class, 'create']);
Route::get('/getQuestionNormal', [QuestionNormalController::class, 'index']);
Route::post('/createTest', [TestController::class, 'create']);
Route::post('/getTests', [TestController::class, 'getTests']);

Route::post('/createQuestionNormal', [QuestionNormalController::class, 'create']);
Route::post('/createAnswerNormal', [AnswerNormalController::class, 'create']);
Route::get('/getAnswerByIdQuestionNormal', [AnswerNormalController::class,'listByIdQuestion']);
Route::post('/doTest', [ExamController::class, 'doTest']);
Route::get('/tests', [TestController::class, 'index']);
Route::get('/tests/{id}', [TestController::class, 'show']);