<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthC;
use App\Http\Controllers\DataC;
use App\Http\Controllers\UserC;
use App\Http\Controllers\CommentC;
use App\Http\Controllers\DataCategoryC;
use App\Http\Controllers\OfficerC;
use App\Http\Controllers\SchoolC;
use App\Http\Controllers\SupervisorC;
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

      Route::post('/school', [SchoolC::class, 'create']);
      Route::put('/school/{id}', [SchoolC::class, 'update'])->whereNumber('id');
      Route::delete('/school/{id}', [SchoolC::class, 'delete'])->whereNumber('id');

      Route::post('/supervisor', [SupervisorC::class, 'create']);
      Route::put('/supervisor/{id}', [SupervisorC::class, 'update'])->whereNumber('id');
      Route::delete('/supervisor/{id}', [SupervisorC::class, 'delete'])->whereNumber('id');

      Route::get('/officers', [OfficerC::class, 'get']);
      Route::post('/officer', [OfficerC::class, 'create']);
      Route::put('/officer/{id}', [OfficerC::class, 'update'])->whereNumber('id');
      Route::delete('/officer/{id}', [OfficerC::class, 'delete'])->whereNumber('id');

      Route::post('/data-category', [DataCategoryC::class, 'create']);
      Route::put('/data-category/{id}', [DataCategoryC::class, 'update'])->whereNumber('id');
      Route::delete('/data-category/{id}', [DataCategoryC::class, 'delete'])->whereNumber('id');
   });

   Route::middleware('ability:school')->group(function() {
      Route::post('/data', [DataC::class, 'create'])->name('data:create');
      Route::put('/data/{id}', [DataC::class, 'update'])->name('data:update');
      Route::post('/data/file/{id}', [DataC::class, 'updateFile'])->name('data:file_update');
   });

   Route::middleware('ability:supervisor')->group(function() {

   });

   Route::middleware('ability:supervisor,officer')->group(function() {
      Route::get('/schools', [SchoolC::class, 'get']);
      Route::get('/school/{id}', [SchoolC::class, 'show'])->whereNumber('id');
      Route::get('/schools/count', [SchoolC::class, 'count']);

      Route::get('/supervisor/{id}', [SupervisorC::class, 'show'])->whereNumber('id');
   });

   Route::middleware('ability:officer')->group(function() {
      Route::get('/supervisors', [SupervisorC::class, 'get']);
      Route::get('/officer/{id}', [OfficerC::class, 'show'])->whereNumber('id');
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

   Route::get('/data-categories', [DataCategoryC::class, 'get']);
});
