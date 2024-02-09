<?php

use App\Http\Controllers\DataCategoryController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DataStatusController;
use App\Http\Controllers\DataTypeController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\CommentController;
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
Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function() {
   Route::post('/logout', [UserController::class, 'logout']);

   Route::middleware('ability:admin')->group(function () {
      Route::delete('/data/{id}', [DataController::class, 'delete']);
      Route::put('/comment/{id}', [CommentController::class, 'update']);

      Route::get('/users', [UserController::class, 'get']);
      Route::get('/users/count', [UserController::class, 'count']);
      Route::post('/user/{id}/activate', [UserController::class, 'activate']);
      Route::post('/user/{id}/inactivate', [UserController::class, 'inactivate']);

      Route::post('/school', [SchoolController::class, 'create']);
      Route::put('/school/{id}', [SchoolController::class, 'update']);
      Route::delete('/school/{id}', [SchoolController::class, 'delete']);

      Route::post('/supervisor', [SupervisorController::class, 'create']);
      Route::put('/supervisor/{id}', [SupervisorController::class, 'update']);
      Route::delete('/supervisor/{id}', [SupervisorController::class, 'delete']);

      Route::get('/officers', [OfficerController::class, 'get']);
      Route::post('/officer', [OfficerController::class, 'create']);
      Route::put('/officer/{id}', [OfficerController::class, 'update']);
      Route::delete('/officer/{id}', [OfficerController::class, 'delete']);

      Route::post('/data-status', [DataStatusController::class, 'create']);
      Route::put('/data-status/{id}', [DataStatusController::class, 'update']);
      Route::delete('/data-status/{id}', [DataStatusController::class, 'delete']);

      Route::post('/data-category', [DataCategoryController::class, 'create']);
      Route::put('/data-category/{id}', [DataCategoryController::class, 'update']);
      Route::delete('/data-category/{id}', [DataCategoryController::class, 'delete']);

      Route::post('/data-type', [DataTypeController::class, 'create']);
      Route::put('/data-type/{id}', [DataTypeController::class, 'update']);
      Route::delete('/data-type/{id}', [DataTypeController::class, 'delete']);

      Route::post('/school-type', [SchoolTypeController::class, 'create']);
      Route::put('/school-type/{id}', [SchoolTypeController::class, 'update']);
      Route::delete('/school-type/{id}', [SchoolTypeController::class, 'delete']);

      Route::post('/subject', [SubjectController::class, 'create']);
      Route::put('/subject/{id}', [SubjectController::class, 'update']);
      Route::delete('/subject/{id}', [SubjectController::class, 'delete']);

      Route::post('/religion', [ReligionController::class, 'create']);
      Route::put('/religion/{id}', [ReligionController::class, 'update']);
      Route::delete('/religion/{id}', [ReligionController::class, 'delete']);
   });

   Route::middleware('ability:school')->group(function () {
      Route::post('/data', [DataController::class, 'create'])->name('createData');
      Route::put('/data/{id}', [DataController::class, 'update'])->name('updateData');
      Route::post('/school-students', [SchoolStudentController::class, 'create']);
      Route::get('/school-students/count', [SchoolStudentController::class, 'countStudents']);
      Route::post('/school-teachers', [SchoolTeacherController::class, 'create']);
      Route::get('/school-teachers/count', [SchoolTeacherController::class, 'countTeachers']);
      Route::post('/data/file/{id}', [DataController::class, 'updateFile'])->name('updateFile');
   });

   Route::middleware('ability:supervisor')->group(function() {
      Route::put('/data/{id}/update-status', [DataController::class, 'updateDataStatus']);
   });

   Route::middleware('ability:supervisor,officer')->group(function () {
      Route::get('/schools', [SchoolController::class, 'get']);
      Route::get('/school/{id}', [SchoolController::class, 'getDetails']);
      Route::get('/schools/count', [SchoolController::class, 'countSchools']);

      Route::get('/supervisors',  [SupervisorController::class, 'get']);
      Route::get('/supervisor/{id}', [SupervisorController::class, 'getDetails']);
   });

   Route::middleware('ability:officer')->group(function() {
      Route::get('/officer/{id}', [OfficerController::class, 'getDetails']);
   });

   Route::get('/data', [DataController::class, 'get']);
   Route::get('/data/{id}', [DataController::class, 'getSingle'])->whereNumber('id');
   Route::post('/data/download', [DataController::class, 'downloadFile']);
   Route::get('/data/count', [DataController::class, 'count']);

   Route::get('/comments/{data_id}', [CommentController::class, 'get']);
   Route::post('/comment', [CommentController::class, 'create']);
   Route::delete('/comment/{id}', [CommentController::class, 'delete']);

   Route::put('/user/{id}', [UserController::class, 'update']);

   Route::get('/school-students', [SchoolStudentController::class, 'getSchoolStudents']);
   Route::get('/school-teachers', [SchoolTeacherController::class, 'getSchoolTeachers']);

   Route::get('/school-students/{id}/growth', [SchoolStudentController::class, 'getSchoolStudentsGrowth'])->whereNumber('id');
   Route::get('/school-teachers/{id}/growth', [SchoolTeacherController::class, 'getSchoolTeachersGrowth'])->whereNumber('id');

   Route::get('/data-statuses', [DataStatusController::class, 'get']);
   Route::get('/data-categories', [DataCategoryController::class, 'get']);
   Route::get('/data-types', [DataTypeController::class, 'get']);
   Route::get('/school-types', [SchoolTypeController::class, 'get']);
   Route::get('/subjects', [SubjectController::class, 'get']);
   Route::get('/religions', [ReligionController::class, 'get']);

   Route::prefix('options')->group(function() {
      Route::get('/schools', [SchoolController::class, 'getOptions']);
      Route::get('/data-categories', [DataCategoryController::class, 'getOPtions']);
      Route::get('/data-types', [DataTypeController::class, 'getOptions']);
      Route::get('/data-status', [DataStatusController::class, 'getOptions']);
      Route::get('/supervisors', [SupervisorController::class, 'getOptions']);
      Route::get('/school-types', [SchoolTypeController::class, 'getOptions']);
      Route::get('/religions', [ReligionController::class, 'getOptions']);
      Route::get('/subjects', [SubjectController::class, 'getOptions']);
   });
});
