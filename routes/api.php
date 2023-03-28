<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChooseQuestionController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CorrectAnswerController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QuestionTestController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
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
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});
Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
     Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);
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
Route::get('/subject/{id}/items', [SubjectController::class, 'items']);
Route::prefix('subject')->middleware('auth:sanctum')->group(function () {
     Route::post('/{id}/items', [TestController::class, 'create']);
     Route::post('/{id}/createItem', [TestController::class, 'create']);
     Route::post('/{id}/updateItem', [TestController::class, 'create']);
     Route::post('/{id}/deleteItem', [TestController::class, 'create']);
});
Route::get('/test/list', [TestController::class, 'list']);
Route::get('/test/{id}/detail', [TestController::class, 'detail']);
Route::prefix('test')->middleware('auth:sanctum')->group(function(){ 
    Route::get('/{id}/exams', [TestController::class, 'getExams']);
    Route::get('/{id}/do', [TestController::class, 'listQuestionTestDo']);// Not send result
    Route::put('/{id}/submit', [ExamController::class, 'submit']);
    Route::get('/{idTest}/exam', [ExamController::class, 'history']);
    Route::get('/{id}/mark', [TestController::class, 'markTest']);
    Route::get('/{id}/update', [TestController::class, 'listQuestionTestUpdate']);// Not send result
    Route::put('/{id}/updateTest', [TestController::class, 'updateTest']);// 
    Route::put('/{id}/updateInfoTest', [TestController::class, 'updateInfoTest']);
    Route::get('/{id}/nummericalQuestion', [TestController::class, 'nummericalQuestion']);
    Route::get('/questions', [QuestionTestController::class, 'listByIdTest']);
    Route::get('/{id}/report', [TestController::class, 'report']);
});
Route::prefix('review')->group(function(){
    Route::post('/create', [TestController::class, 'import']);
    Route::get('/{id}/do', [AnswerController::class, 'updateNormalAnswer']);
    Route::get('/{id}/delete', [AnswerController::class, 'deleteNormalAnswer']);
    Route::get('/{id}/show', [AnswerController::class, 'showNormalAnswer']);
});
Route::prefix('exam')->middleware('auth:sanctum')->group(function () {
    Route::get('/list', [ExamController::class, 'list']);
    Route::put('/do', [ExamController::class, 'do']);
    Route::put('/{idExam}/mark', [ExamController::class, 'mark']);
    Route::post('/createNew', [ExamController::class, 'createNew']);
    Route::post('/rework', [ExamController::class, 'rework']);
    Route::get('/{id}/report', [ExamController::class, 'reportExam']);
});
Route::get('/listUser/{id}', [ClassroomController::class, 'listUser']);

Route::prefix('questionNormal')->group(function(){
    Route::get('/list', [QuestionController::class, 'listQuestionNormals']);
    Route::get('/getAnswerByIdQuestionNormal', [AnswerNormalController::class, 'listByIdQuestion']);
    Route::get('/questionNormals', [QuestionController::class, 'index']);
    Route::post('/createAnswer', [AnswerNormalController::class, 'create']);
    Route::get('/{id}/answers', [AnswerController::class,'list']);
});
Route::prefix('question')->group(function(){
    Route::post('/handle', [QuestionController::class, 'handle']);
    Route::get('/create', [AnswerNormalController::class, 'create']);
    Route::get('/{id}/update', [QuestionController::class, 'update']);
    Route::post('/{id}/delete', [AnswerNormalController::class, 'delete']);
    Route::get('/{id}/show', [AnswerController::class,'show']);
});
Route::prefix('answerNormal')->group(function(){
    Route::get('/create', [AnswerController::class, 'createNormalAnswer']);
    Route::get('/{id}/update', [AnswerController::class, 'updateNormalAnswer']);
    Route::get('/{id}/delete', [AnswerController::class, 'deleteNormalAnswer']);
    Route::get('/{id}/show', [AnswerController::class, 'showNormalAnswer']);
});
