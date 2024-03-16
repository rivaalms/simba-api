<?php

use App\Http\Controllers\AuthC;
use App\Http\Controllers\DataC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthC::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
   Route::post('/logout', [AuthC::class, 'logout']);
   Route::get('/me', [AuthC::class, 'me']);

   Route::middleware('ability:admin')->group(function() {
      Route::delete('/data/{id}', [DataC::class, 'delete'])->whereNumber('id');
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
});
