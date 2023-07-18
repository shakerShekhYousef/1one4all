<?php

namespace routes\api\v1;

use App\Http\Controllers\Api\auth\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\ExerciseGroupController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PaymentPaypalController;
use App\Http\Controllers\Api\PaypalController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\FileController;
use App\Models\ExerciseGroup;
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
//route::post('/Register', [AuthApiController::class, 'Register']);
//route::post('/Login', [AuthApiController::class, 'Login']);
//route::get('/GetUserInfo', [AuthApiController::class, 'GetUserInfo']);
//route::post('/UpdateUserInfo', [AuthApiController::class, 'UpdateUserInfo']);
Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', [AuthController::class, 'login'])->name('api.login');
    Route::post('register', [AuthController::class, 'register'])->name('api.register');
    Route::get('logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('user', [AuthController::class, 'user'])->name('api.user');
});
//user routes
Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
    Route::post('update', [UserController::class, 'update'])->name('user.update');
    Route::post('updatePassword', [UserController::class, 'updatePassword'])->name('user.updatePassword');
    //get my trainers
    Route::get('myTrainers', [UserController::class, 'myTrainers'])->name('user.myTrainers');
    //get my players
    Route::get('myPlayers', [UserController::class, 'myPlayers'])->name('user.myPlayers');
    //reset profile image
    Route::post('resetProfileImage', [UserController::class, 'resetProfileImage'])
        ->name('user.resetProfileImage');
});
//certificate routes
Route::resource('certificates', CertificateController::class)->middleware('auth:api')
    ->except('update');
Route::post('certificates/update', [CertificateController::class, 'update'])->middleware('auth:api')
    ->name('certificates.update');
//request routes
Route::group(['prefix' => 'request', 'middleware' => 'auth:api'], function () {
    Route::get('getOwnRequest', [\App\Http\Controllers\Api\RequestController::class, 'getOwnRequest'])->name('request.getOwnRequest');
    Route::get('show/{request_id}', [\App\Http\Controllers\Api\RequestController::class, 'show'])->name('request.show');
    Route::post('send', [\App\Http\Controllers\Api\RequestController::class, 'store'])->name('request.send');
    Route::put('changeType/{request_id}', [\App\Http\Controllers\Api\RequestController::class, 'changeType'])->name('request.changeType');
});
//categories routes
Route::resource('categories', CategoryController::class);
//plan routes
Route::resource('plans', PlanController::class)->middleware('auth:api');
//device firebase token
Route::group(['middleware' => 'auth:api', 'prefix' => 'devices'], function () {
    Route::post('saveDeviceToken', [DeviceController::class, 'saveDeviceToken'])->name('saveDeviceToken');
    Route::post('deleteDeviceToken', [DeviceController::class, 'deleteDeviceToken'])->name('deleteDeviceToken');
});
//level
Route::get('levels', [LevelController::class, 'index'])->name('levels');
//Exercise routes
Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('exercises', ExerciseController::class);
    Route::post('exercises/update', [ExerciseController::class, 'updateDate'])->name('exercises.updateDate');
    //get all trainers
    Route::get('user/trainers', [UserController::class, 'getTrainers'])->name('user.trainers');
    //get all players
    Route::get('user/players', [UserController::class, 'getPlayers'])->name('user.players');
});
//Notifications
Route::get('notifications', [UserController::class, 'getNotifications'])
    ->name('getNotifications')
    ->middleware('auth:api');
//Exercise group routes
Route::resource('exercise_group', ExerciseGroupController::class)->middleware('auth:api');
//get all messages
Route::group(['middleware' => 'auth:api', 'prefix' => 'chat'], function () {
    Route::get('getMessages', [ChatController::class, 'getMessages'])->name('getMessages');
    Route::get('getFriends', [ChatController::class, 'getFriends'])->name('getFriends');
});
//send notification api
Route::post('/send_notification', [NotificationController::class, 'sendNotification'])
    ->name('send_notification');
//store files
Route::post('files', [\App\Http\Controllers\Api\FileController::class, 'store'])
    ->middleware('auth:api')
    ->name('files.store');
//paypal payment
Route::group(['prefix' => 'paypal', 'middleware' => 'auth:api'], function () {
    Route::post('payment',[PaymentController::class,'payment'])->name('payment');
});
Route::post('payment/paypal',[PaymentPaypalController::class,'paypal'])->name('payment.paypal');
Route::get('payment/status/{order_id}',[PaymentPaypalController::class,'getPaymentStatus'])->name('status');
