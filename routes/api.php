<?php

use App\Http\Controllers\DataCategoryController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DataStatusController;
use App\Http\Controllers\DataTypeController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\ReligionController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SchoolStudentController;
use App\Http\Controllers\SchoolTeacherController;
use App\Http\Controllers\SchoolTypeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SupervisorController;
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

   Route::get('/data', [DataController::class, 'get']);
   Route::get('/data/count', [DataController::class, 'count']);
   Route::post('/data', [DataController::class, 'create']);
   Route::put('/data/{id}', [DataController::class, 'update']);
   Route::post('/data/file/{id}', [DataController::class, 'updateFile']);
   Route::post('/data/download', [DataController::class, 'downloadFile']);
   Route::delete('/data', [DataController::class, 'delete']);

   Route::get('/users/count', [UserController::class, 'count']);

   Route::get('/schools', [SchoolController::class, 'get']);
   Route::get('/school/{id}', [SchoolController::class, 'getDetails']);
   Route::post('/school', [SchoolController::class, 'create']);
   Route::put('/school/{id}', [SchoolController::class, 'update']);
   Route::delete('/school', [SchoolController::class, 'delete']);

   Route::get('/school-students', [SchoolStudentController::class, 'getSchoolStudents']);
   Route::post('/school-students', [SchoolStudentController::class, 'create']);

   Route::get('/school-teachers', [SchoolTeacherController::class, 'getSchoolTeachers']);
   Route::post('/school-teachers', [SchoolTeacherController::class, 'create']);

   Route::get('/supervisors',  [SupervisorController::class, 'get']);
   Route::get('/supervisor/{id}', [SupervisorController::class, 'getDetails']);
   Route::post('/supervisor', [SupervisorController::class, 'create']);
   Route::put('/supervisor/{id}', [SupervisorController::class, 'update']);
   Route::delete('/supervisor', [SupervisorController::class, 'delete']);

   Route::get('/officers', [OfficerController::class, 'get']);
   Route::post('/officer', [OfficerController::class, 'create']);
   Route::put('/officer/{id}', [OfficerController::class, 'update']);
   Route::delete('/officer', [OfficerController::class, 'delete']);

   Route::get('/options/schools', [SchoolController::class, 'getOptions']);
   Route::get('/options/data-categories', [DataCategoryController::class, 'getOPtions']);
   Route::get('/options/data-types', [DataTypeController::class, 'getOptions']);
   Route::get('/options/data-status', [DataStatusController::class, 'getOptions']);
   Route::get('/options/supervisors', [SupervisorController::class, 'getOptions']);
   Route::get('/options/school-types', [SchoolTypeController::class, 'getOptions']);

   Route::get('/religions', [ReligionController::class, 'get']);

   Route::get('/data-statuses', [DataStatusController::class, 'get']);
   Route::post('/data-status', [DataStatusController::class, 'create']);
   Route::put('/data-status/{id}', [DataStatusController::class, 'update']);
   Route::delete('/data-status', [DataStatusController::class, 'delete']);

   Route::get('/data-categories', [DataCategoryController::class, 'get']);
   Route::post('/data-category', [DataCategoryController::class, 'create']);
   Route::put('/data-category/{id}', [DataCategoryController::class, 'update']);
   Route::delete('/data-category', [DataCategoryController::class, 'delete']);

   Route::get('/data-types', [DataTypeController::class, 'get']);
   Route::post('/data-type', [DataTypeController::class, 'create']);
   Route::put('/data-type/{id}', [DataTypeController::class, 'update']);
   Route::delete('/data-type', [DataTypeController::class, 'delete']);

   Route::get('/school-types', [SchoolTypeController::class, 'get']);
   Route::post('/school-type', [SchoolTypeController::class, 'create']);
   Route::put('/school-type/{id}', [SchoolTypeController::class, 'update']);
   Route::delete('/school-type', [SchoolTypeController::class, 'delete']);

   Route::get('/subjects', [SubjectController::class, 'get']);
   Route::post('/subject', [SubjectController::class, 'create']);
   Route::put('/subject/{id}', [SubjectController::class, 'update']);
   Route::delete('/subject', [SubjectController::class, 'delete']);
});
