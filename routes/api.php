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

   Route::get('/data', [DataController::class, 'getData']);
   Route::post('/data', [DataController::class, 'createData']);
   Route::put('/data/{id}', [DataController::class, 'updateData']);
   Route::post('/data/file/{id}', [DataController::class, 'updateDataFile']);
   Route::post('/data/download', [DataController::class, 'downloadData']);
   Route::delete('/data', [DataController::class, 'deleteData']);

   Route::get('/schools', [SchoolController::class, 'getSchool']);
   Route::get('/school/{id}', [SchoolController::class, 'getSchoolDetails']);
   Route::post('/school', [SchoolController::class, 'createSchool']);
   Route::put('/school/{id}', [SchoolController::class, 'updateSchool']);
   Route::delete('/school', [SchoolController::class, 'deleteSchool']);

   Route::get('/school-students', [SchoolStudentController::class, 'getSchoolStudents']);
   Route::post('/school-students', [SchoolStudentController::class, 'create']);

   Route::get('/school-teachers', [SchoolTeacherController::class, 'getSchoolTeachers']);
   Route::post('/school-teachers', [SchoolTeacherController::class, 'create']);

   Route::get('/supervisors',  [SupervisorController::class, 'getSupervisor']);
   Route::get('/supervisor/{id}', [SupervisorController::class, 'getSupervisorDetails']);
   Route::post('/supervisor', [SupervisorController::class, 'createSupervisor']);
   Route::put('/supervisor/{id}', [SupervisorController::class, 'updateSupervisor']);
   Route::delete('/supervisor', [SupervisorController::class, 'deleteSupervisor']);

   Route::get('/officers', [OfficerController::class, 'getOfficer']);
   Route::post('/officer', [OfficerController::class, 'createOfficer']);
   Route::put('/officer/{id}', [OfficerController::class, 'updateOfficer']);
   Route::delete('/officer', [OfficerController::class, 'deleteOfficer']);

   Route::get('/users', [UserController::class, 'getUser']);

   Route::get('/options/schools', [SchoolController::class, 'getSchoolOptions']);
   Route::get('/options/data-categories', [DataCategoryController::class, 'getDataCategoryOptions']);
   Route::get('/options/data-types', [DataTypeController::class, 'getDataTypeOptions']);
   Route::get('/options/data-status', [DataStatusController::class, 'getDataStatusOptions']);
   Route::get('/options/supervisors', [SupervisorController::class, 'getSupervisorOptions']);
   Route::get('/options/school-types', [SchoolTypeController::class, 'getSchoolTypeOptions']);

   Route::get('/religions', [ReligionController::class, 'getReligions']);
   Route::get('/subjects', [SubjectController::class, 'getSubjects']);

   Route::get('/data-statuses', [DataStatusController::class, 'get']);
   Route::post('/data-status', [DataStatusController::class, 'create']);
   Route::put('/data-status/{id}', [DataStatusController::class, 'update']);
   Route::delete('/data-status', [DataStatusController::class, 'delete']);
});
