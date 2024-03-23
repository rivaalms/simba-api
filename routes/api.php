<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthC;
use App\Http\Controllers\DataC;
use App\Http\Controllers\UserC;
use App\Http\Controllers\SchoolC;
use App\Http\Controllers\CommentC;
use App\Http\Controllers\OfficerC;
use App\Http\Controllers\SubjectC;
use App\Http\Controllers\DataTypeC;
use App\Http\Controllers\ReligionC;
use App\Http\Controllers\DataStatusC;
use App\Http\Controllers\SchoolTypeC;
use App\Http\Controllers\SupervisorC;
use App\Http\Controllers\DataCategoryC;
use App\Http\Controllers\SelectOptionC;
use App\Http\Controllers\SchoolStudentC;
use App\Http\Controllers\SchoolTeacherC;

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

      Route::post('/data-status', [DataStatusC::class, 'create']);
      Route::put('/data-status/{id}', [DataStatusC::class, 'update'])->whereNumber('id');
      Route::delete('/data-status/{id}', [DataStatusC::class, 'delete'])->whereNumber('id');

      Route::post('/data-type', [DataTypeC::class, 'create']);
      Route::put('/data-type/{id}', [DataTypeC::class, 'update'])->whereNumber('id');
      Route::delete('/data-type/{id}', [DataTypeC::class, 'delete'])->whereNumber('id');

      Route::post('/school-type', [SchoolTypeC::class, 'create']);
      Route::put('/school-type/{id}', [SchoolTypeC::class, 'update'])->whereNumber('id');
      Route::delete('/school-type/{id}', [SchoolTypeC::class, 'delete'])->whereNumber('id');

      Route::post('/religion', [ReligionC::class, 'create']);
      Route::put('/religion/{id}', [ReligionC::class, 'update'])->whereNumber('id');
      Route::delete('/religion/{id}', [ReligionC::class, 'delete'])->whereNumber('id');

      Route::post('/subject', [SubjectC::class, 'create']);
      Route::put('/subject/{id}', [SubjectC::class, 'update'])->whereNumber('id');
      Route::delete('/subject/{id}', [SubjectC::class, 'delete'])->whereNumber('id');
   });

   Route::middleware('ability:school')->group(function() {
      Route::post('/data', [DataC::class, 'create'])->name('data:create');
      Route::put('/data/{id}', [DataC::class, 'update'])->name('data:update');
      Route::post('/data/file/{id}', [DataC::class, 'updateFile'])->name('data:file_update');

      Route::post('/school-students', [SchoolStudentC::class, 'create']);
      Route::get('/school-students/count', [SchoolStudentC::class, 'count']);

      Route::post('/school-teachers', [SchoolTeacherC::class, 'create']);
      Route::get('/school-teachers/count', [SchoolTeacherC::class, 'count']);
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
   Route::get('/data-statuses', [DataStatusC::class, 'get']);
   Route::get('/data-types', [DataTypeC::class, 'get']);
   Route::get('/school-types', [SchoolTypeC::class, 'get']);
   Route::get('/religions', [ReligionC::class, 'get']);
   Route::get('/subjects', [SubjectC::class, 'get']);

   Route::get('/school-students', [SchoolStudentC::class, 'get']);
   Route::get('/school-students/{id}/growth', [SchoolStudentC::class, 'growth'])->whereNumber('id');

   Route::get('/school-teachers', [SchoolTeacherC::class, 'get']);
   Route::get('/school-teachers/{id}/growth', [SchoolTeacherC::class, 'growth'])->whereNumber('id');

   Route::prefix('options')->group(function () {
      Route::get('/data-categories', [SelectOptionC::class, 'getDataCategories']);
      Route::get('/data-status', [SelectOptionC::class, 'getDataStatuses']);
      Route::get('/data-types', [SelectOptionC::class, 'getDataTypes']);
      Route::get('/school-types', [SelectOptionC::class, 'getSchoolTypes']);
      Route::get('/religions', [SelectOptionC::class, 'getReligions']);
      Route::get('/subjects', [SelectOptionC::class, 'getSubjects']);
      Route::get('/schools', [SelectOptionC::class, 'getSchools']);
      Route::get('/supervisors', [SelectOptionC::class, 'getSupervisors']);
   });
});
