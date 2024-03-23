<?php

namespace App\Http\Controllers;

use App\Models\DataCategory;
use App\Models\DataStatus;
use App\Models\DataType;
use App\Models\Religion;
use App\Models\School;
use App\Models\SchoolType;
use App\Models\Subject;
use Illuminate\Http\Request;

class SelectOptionC extends Controller
{
   public function getDataCategories() {
      $data = DataCategory::select('name as label', 'id as value')
         ->get()
         ->toArray();
      return parent::apiResponse($data);
   }

   public function getDataStatuses() {
      $data = DataStatus::select('name as label', 'id as value')
         ->get()
         ->toArray();
      return parent::apiResponse($data);
   }

   public function getDataTypes() {
      $data = DataType::select('name as label', 'id as value')
         ->get()
         ->toArray();
      return parent::apiResponse($data);
   }

   public function getSchoolTypes() {
      $data = SchoolType::select('name as label', 'id as value')
         ->get()
         ->toArray();
      return parent::apiResponse($data);
   }

   public function getReligions() {
      $data = Religion::select('name as label', 'id as value')
         ->get()
         ->toArray();
      return parent::apiResponse($data);
   }

   public function getSubjects() {
      $data = Subject::select('name as label', 'id as value')
         ->get()
         ->toArray();
      return parent::apiResponse($data);
   }

   public function getSchools() {
      $schools = School::select('id')
         ->supervisor(request('supervisor'))
         ->get();
      $data = [];

      foreach ($schools as $s) {
         $data[] = [
            'label' => $s->user->name,
            'value' => $s->id
         ];
      }
      return parent::apiResponse($data);
   }

   public function getSupervisors() {
      $supervisors = School::select('id')
         ->distinct()
         ->get();
      $data = [];

      foreach ($supervisors as $s) {
         $data[] = [
            'label' => $s->user->name,
            'value' => $s->id
         ];
      }
      return parent::apiResponse($data);
   }
}
