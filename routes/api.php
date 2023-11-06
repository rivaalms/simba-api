<?php

use App\Http\Controllers\DataCategoryController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DataStatusController;
use App\Http\Controllers\DataTypeController;
use App\Http\Controllers\SchoolController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
   Route::post('/logout', [UserController::class, 'logout']);

   Route::get('/data', [DataController::class, 'getData']);
   Route::post('/data', [DataController::class, 'createData']);
   Route::put('/data/{id}', [DataController::class, 'updateData']);
   Route::post('/data/file/{id}', [DataController::class, 'updateDataFile']);
   Route::post('/data/download', [DataController::class, 'downloadData']);
   Route::delete('/data', [DataController::class, 'deleteData']);

   Route::get('/schools', [SchoolController::class, 'getSchool']);
   Route::get('/users', [UserController::class, 'getUser']);

   Route::get('/options/schools', [SchoolController::class, 'getSchoolOptions']);
   Route::get('/options/data-categories', [DataCategoryController::class, 'getDataCategoryOptions']);
   Route::get('/options/data-types', [DataTypeController::class, 'getDataTypeOptions']);
   Route::get('/options/data-status', [DataStatusController::class, 'getDataStatusOptions']);
});
