<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\TodoController;
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

Route::post('/register', [RegisterController::class, 'register'])->name('user.register');
Route::post('/login', [LoginController::class, 'login'])->name('user.login');

Route::get('/email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/todos/search/{title}', [TodoController::class, 'search']);
    Route::apiResources([
        'todos' => TodoController::class,
    ]);
});

