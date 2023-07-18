<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegisterController as ControllersRegisterController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

// route::middleware(['auth:api'])->get('logout', [RegisterController::class, 'logout'])->name('logout');

route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
});

route::prefix('users')->middleware('auth')->group(function () {
    // Route::get('/createuserindex', [App\Http\Controllers\HomeController::class, 'createuserindex'])->name('web_create_user_index');
    Route::get('/createadminindex', [HomeController::class, 'createadminindex'])->name('web_create_admin_index');
    Route::post('/createuser', [HomeController::class, 'createuser'])->name('web_create_user');
    Route::get('/usersindex', [HomeController::class, 'usersindex'])->name('web_users_index');
    Route::get('/getusers', [HomeController::class, 'getusers'])->name('web_get_users');
    Route::get('/getuser/{id?}', [HomeController::class, 'getuser'])->name('web_get_user');
    Route::post('/edituser/{id?}', [HomeController::class, 'edituser'])->name('web_edit_user');
    Route::post('/changePassword/{id?}', [HomeController::class, 'changePassword'])->name('web_change_password');
    Route::delete('/deleteuser', [HomeController::class, 'deleteuser'])->name('web_delete_user');
    Route::get('/approvetrainerindex/{id?}', [HomeController::class, 'approvetrainerindex'])->name('web_approve_trainer_index');
    Route::get('/approvetrainer/{id?}', [HomeController::class, 'approvetrainer'])->name('web_approve_trainer');
    Route::get('/deapprovetrainer/{id?}', [HomeController::class, 'deapprovetrainer'])->name('web_deapprove_trainer');
    Route::get('/showuserindex/{id?}', [HomeController::class, 'showuserindex'])->name('web_show_user_index');
});

route::prefix('requests')->middleware('auth')->group(function () {
    Route::get('/getAllRequestsIndex', [RequestController::class, 'getAllRequestsindex'])->name('web_get_All_Requests_index');
    Route::get('/getAllRequests', [RequestController::class, 'getAllRequests'])->name('web_get_All_Requests');
    Route::delete('/deleterequest', [RequestController::class, 'deleterequest'])->name('web_delete_request');
});

Route::post('loginWithOtp',  [RegisterController::class, 'loginWithOtp'])->name('login_WithOtp');
Route::get('loginWithOtp', function () {
    return view('auth/OtpLogin');
})->name('loginWithOtp');

Route::post('sendOtp', [RegisterController::class, 'sendOtp'])->name('send_Otp');

Route::group(['middleware' => 'auth'], function () {
    Route::get('otheredit/{id?}', [HomeController::class, 'otheredit'])->name('web_other_edit');
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\HomeController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\HomeController@update']);
    Route::get('icons', function () {
        return view('pages.icons');
    })->name('icons');
    Route::get('table-list', function () {
        return view('pages.tables');
    })->name('table');
});
