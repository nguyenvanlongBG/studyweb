<?php

use App\Http\Controllers\ChooseQuestionController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CorrectAnswerController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QuestionTestController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Models\ChooseQuestionTest;
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
Route::prefix('post')->group(function(){
    Route::post('/create', [PostController::class, 'create']);
    Route::get('/list', [PostController::class, 'index']);
    Route::post('/update', [PostController::class, 'update']);
    Route::post('/delete', [PostController::class, 'delete']);
    // Route::get('/createPost/{id}', [PostController::class, 'create']);
    Route::put('/approve/{id}', [PostController::class, 'approvePost']);
});
Route::prefix('group')->group(function(){
    Route::get('/list', [GroupController::class, 'index']);
    Route::post('/create', [GroupController::class, 'create']);
    Route::post('/update', [GroupController::class, 'create']);
    Route::post('/createRequest', [UserController::class, 'createRequest']);
    Route::post('/approveUser/{id}', [GroupController::class, 'approveUser']);
});
Route::prefix('test')->group(function(){
    Route::post('/create', [TestController::class, 'create']);
    Route::get('/show', [TestController::class, 'show']);
    Route::get('/list', [TestController::class, 'list']);
    Route::get('/{id}', [TestController::class, 'listQuestionTest']);
    Route::get('/questions', [QuestionTestController::class, 'listByIdTest']);
    Route::post('/createResult', [CorrectAnswerController::class, 'create']);
    Route::post('/createChoose', [ChooseQuestionTest::class, 'create']);
    Route::post('/updateChoose', [ChooseQuestionTest::class, 'update']);
    Route::post('/deleteChoose', [ChooseQuestionTest::class, 'create']);
    Route::post('/chooses', [ChooseQuestionTest::class, 'listBy']);
   
    Route::post('/showQuestion', [QuestionTestController::class, 'show']);
    Route::post('/createQuestion', [QuestionTestController::class, 'create']);
    Route::post('/updateQuestion', [QuestionTestController::class, 'update']);
    Route::post('/deleteQuestion', [QuestionTestController::class, 'delete']);
});

Route::get('/listUser/{id}', [ClassroomController::class, 'listUser']);

Route::prefix('questionNormal')->group(function(){
    Route::get('/list', [QuestionController::class, 'listQuestionNormals']);
     Route::get('/getAnswerByIdQuestionNormal', [AnswerNormalController::class, 'listByIdQuestion']);
    Route::get('/questionNormals', [QuestionController::class, 'index']);
    Route::post('/createAnswer', [AnswerNormalController::class, 'create']);
    Route::get('/{id}/answers', [AnswerController::class,'list']);
});
