<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SchoolController extends Controller
{
   public function getSchool(Request $request) {
      $schools = School::filter(request(['search', 'type', 'supervisor']))->latest()->paginate($request->per_page)->withQueryString();

      return $this->apiResponse($schools);
   }

   public function getSchoolDetails(Request $request, int $id) {
      $school = School::find($id);
      return $this->apiResponse($school);
   }

   public function createSchool(Request $request) {
      $validator = $request->validate([
         'name' => 'required|string',
         'email' => 'required|email|unique:users,email',
         'password' => 'required|min:8|string',
         'school_type_id' => 'required|numeric',
         'supervisor_id' => 'required|numeric',
         'principal' => 'nullable|string',
         'address' => 'nullable|string'
      ]);

      if ($validator['password'] !== $request->confirm_password) return $this->apiResponse(null, 'Confirm password does not match the password', 422);

      $school = School::create([
         'school_type_id' => $validator['school_type_id'],
         'supervisor_id' => $validator['supervisor_id'],
         'principal' => $validator['principal'],
         'address' => $validator['address']
      ]);

      $user = User::create([
         'name' => $validator['name'],
         'email' => $validator['email'],
         'userable_type' => School::MORPH_ALIAS,
         'userable_id' => $school->id,
         'password' => Hash::make($validator['password'])
      ]);

      return $this->apiResponse($user->load(['userable']), 'New school created successfully', 201);
   }

   public function updateSchool(Request $request, int $id) {
      $school = School::find($id);

      $validator = $request->validate([
         'name' => 'required|string',
         'email' => "required|email|unique:users,email,{$school->user->id}",
         'school_type_id' => 'required|numeric',
         'supervisor_id' => 'required|numeric',
         'principal' => 'nullable|string',
         'address' => 'nullable|string'
      ]);

      $school->update([
         'school_type_id' => $validator['school_type_id'],
         'supervisor_id' => $validator['supervisor_id'],
         'principal' => $validator['principal'],
         'address' => $validator['address']
      ]);

      $user = User::where('userable_id', $id)->where('userable_type', School::MORPH_ALIAS)->update([
         'name' => $validator['name'],
         'email' => $validator['email']
      ]);

      return $this->apiResponse(true, 'School updated successfully');
   }

   public function deleteSchool(Request $request) {
      $request->validate([
         'id' => 'required'
      ]);

      $school = School::find($request->id);
      if (!$school) return $this->apiResponse(null, 'School not found', 422);
      $school->delete();
      User::where('userable_type', School::MORPH_ALIAS)->where('userable_id', $request->id)->delete();
      return $this->apiResponse(true, 'School has been deleted');
   }

   public function getSchoolOptions() {
      $schools = School::select('id')->get();
      $data = [];

      foreach ($schools as $s) {
         array_push($data, [
            'label' => $s->user->name,
            'value' => $s->id
         ]);
      }
      return $this->apiResponse($data);
   }
}
