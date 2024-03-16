<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthC;
use App\Http\Controllers\DataC;
use App\Http\Controllers\UserC;
use App\Http\Controllers\CommentC;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthC::class, 'login']);
Route::post('/forgot-password', [AuthC::class, 'forgotPassword']);
Route::post('/reset-password', [AuthC::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function() {
   Route::post('/logout', [AuthC::class, 'logout']);
   Route::get('/me', [AuthC::class, 'me']);

   Route::middleware('ability:admin')->group(function() {
      Route::delete('/data/{id}', [DataC::class, 'delete'])->whereNumber('id');

      Route::get('/users', [UserC::class, 'get']);
      Route::get('/users/count', [UserC::class, 'count']);
      Route::post('/user/{id}/activate', [UserC::class, 'activate'])->whereNumber('id');
      Route::post('/user/{id}/inactivate', [UserC::class, 'inactivate'])->whereNumber('id');
   });

   Route::middleware('ability:school')->group(function() {
      Route::post('/data', [DataC::class, 'create'])->name('data:create');
      Route::put('/data/{id}', [DataC::class, 'update'])->name('data:update');
      Route::post('/data/file/{id}', [DataC::class, 'updateFile'])->name('data:file_update');
   });

   Route::middleware('ability:supervisor')->group(function() {

   });

   Route::middleware('ability:supervisor,officer')->group(function() {

   });

   Route::middleware('ability:officer')->group(function() {

   });

   Route::get('/data', [DataC::class, 'get']);
   Route::get('/data/{id}', [DataC::class, 'show'])->whereNumber('id');
   Route::post('/data/download', [DataC::class, 'downloadFile']);
   Route::get('/data/count', [DataC::class, 'count']);

   Route::get('/comments/{data_id}', [CommentC::class, 'get'])->whereNumber('data_id');
   Route::get('/comment', [CommentC::class, 'create']);
   Route::put('/comment/{id}', [CommentC::class, 'update'])->whereNumber('id');
   Route::delete('/comment/{id}', [CommentC::class, 'delete'])->whereNumber('id');

   Route::put('/user/{id}', [UserC::class, 'update'])->whereNumber('id');
});
