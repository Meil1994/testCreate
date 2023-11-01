<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreateNewTest;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\EditTestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TestTakerController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\TestAnswersController;
use App\Http\Controllers\AccountVerifyController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\IndividualResponseController;
use App\Http\Controllers\IndividualTestRespondersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LandingController::class, 'landingPage'])->middleware('guest');

Route::middleware(['auth'])->group(function(){

    Route::get('/api/edit/{id}', [EditTestController::class, 'getTestDetails']);
    Route::post('/api/delete/question/{questionId}', [EditTestController::class, 'deleteQuestion']);
    Route::put('/api/update/{id}', [EditTestController::class, 'update']);
    Route::post('/api/edit/new/question', [EditTestController::class, 'createNewEditQuestion']);
    Route::post('/api/create/new/edit/answer', [EditTestController::class, 'createNewAnswer']);
    Route::post('/api/delete/set/{setId}', [EditTestController::class, 'deleteSet']);
    Route::post('/api/create/new/editset/{testId}', [EditTestController::class, 'createNewEditSet']);

    Route::post('/api/test/create', [CreateNewTest::class, 'createTest']);
    
    Route::get('/api/tests', [DashboardController::class, 'index']);
    Route::put('/api/tests/{test}/change-status', [DashboardController::class, 'updateStatus']);
    Route::get('/api/users/search', [DashboardController::class, 'search']);
    Route::post('/api/delete/test/{testId}', [DashboardController::class, 'deleteTest']);
    Route::get('/api/get/results/taker', [DashboardController::class, 'getResultsTestTaker']);
    
    Route::get('/api/get/test/takers', [TestTakerController::class, 'getTestTaker']);
    Route::get('/api/get/test', [TestTakerController::class, 'getTests']);
    Route::get('/api/test/takers/search', [TestTakerController::class, 'search']);
    
    Route::get('/api/get/individual/{id}', [IndividualResponseController::class, 'getIndividualResponse']);
    Route::get('/api/get/tests/{id}', [IndividualResponseController::class, 'getIndividualTest']);
    Route::put('/api/responder/{responder}/change-status', [IndividualResponseController::class, 'updateResponderStatus']);

    Route::get('/api/get/user', [AccountController::class, 'getUser']);
    Route::put('/api/update/target/user', [AccountController::class, 'updateTargerUser']);
    Route::post('/api/create/new/note', [AccountController::class, 'createNote']);
    Route::get('/api/get/updated/notes', [AccountController::class, 'getNotes']);
    Route::post('/api/delete/note/{noteId}', [AccountController::class, 'deleteNote']);

    Route::get('/api/individual/test/{id}', [IndividualTestRespondersController::class, 'getIndividualTestResponders']);

    Route::post('/api/verify/email/address', [AccountVerifyController::class, 'sendVerifyEmail']);
    Route::get('/api/user/verify/email/{id}', [AccountVerifyController::class, 'updateVerify']);

    Route::get('/api/get/chart/test', [ChartController::class, 'getChartTest']);
    Route::get('/api/get/test/search/{id}', [ChartController::class, 'getIndividualChartTest']);
});


Route::get('/user/signin', [UserController::class, 'login'])->name('login');
Route::get('/user/signup', [UserController::class, 'signup'])->middleware('guest');
Route::post('/api/create/user', [UserController::class, 'createUser']);
Route::post('/api/validate/user', [UserController::class, 'validateUser']);
Route::post('/api/logout', [UserController::class, 'logout'])->middleware('auth');

Route::get('/api/submit/answer/{id}', [TestAnswersController::class, 'getAnswers']);
Route::get('/api/test/individual/number/{id}', [TestAnswersController::class, 'getTestAnswer']);
Route::post('/api/create/response', [TestAnswersController::class, 'createResponse'])->name('response');
Route::post('/api/new/answer', [TestAnswersController::class, 'createNewAnswers']);

Route::get('/user/forgot/password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('/api/reset/password', [ForgotPasswordController::class, 'sendResetEmail']);
Route::get('/user/update/password/{token}', [ForgotPasswordController::class, 'updatePassword']);
Route::post('/api/update/password/{token}', [ForgotPasswordController::class, 'postReset']);

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');


Route::fallback(function () {
    return redirect('/');
});

Route::any('/{any}', function () {
    return redirect('/');
})->where('any', '.*');



