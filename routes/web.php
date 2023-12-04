<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
   return view('welcome');
});

Route::get('/reset-password/{token}', function (string $token) {
   return view('reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('password.update');
