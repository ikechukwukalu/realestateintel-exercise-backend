<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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

Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'register'])->name('register');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::middleware('auth:sanctum')->post('logout', [LogoutController::class, 'logout'])->name('logout');
    Route::get('verify/email/{id}', [VerificationController::class, 'verifyUserEmail'])->name('verification.verify');
    Route::middleware('auth:sanctum')->post('resend/verify/email', [VerificationController::class, 'resendUserEmailVerification'])->name('verification.resend');
    Route::post('forgot/password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgotPassword');
    Route::post('reset/password', [ResetPasswordController::class, 'resetPassword'])->name('resetPassword');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('external-books', [ApiController::class, 'externalBooks'])->name('externalBooks');
    Route::prefix('v1')->group(function () {
        Route::post('books', [ApiController::class, 'createBook'])->name('createBook');
        Route::get('books/{id?}', [ApiController::class, 'listBooks'])->name('listBooks');
        Route::patch('books/{id}', [ApiController::class, 'updateBook'])->name('updateBook');
        Route::delete('books/{id}', [ApiController::class, 'deleteBook'])->name('deleteBook');
    });
});
