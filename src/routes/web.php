<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestController;
use App\Http\Controllers\VerifyController;
use App\Models\Rest;
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

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/', [AuthController::class, 'index']);
    Route::get('/reststart', [RestController::class, 'startRest']);
    Route::get('/restend', [RestController::class, 'endRest']);
    Route::get('/workin', [AttendanceController::class, 'startWork']);
    Route::get('/workout', [AttendanceController::class, 'endWork']);
    Route::get('/attendance', [AttendanceController::class, 'showAtte']);
    Route::get('/attendance/{num}', [AttendanceController::class, 'showAtte']);
    Route::get('/user', [AttendanceController::class, 'showUser']);
    Route::get('/personal/{id}', [AttendanceController::class, 'showPersonal']);
});
Route::get('/email/verify', [VerifyController::class, 'sendEmail'])
    ->middleware('auth')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerifyController::class, 'verify'])
    ->middleware(['auth', 'signed'])->name('verification.verify');