<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OfficerController extends Controller
{
   public function get (Request $request) {
      $officers = Officer::filter(request(['search']))->latest()->paginate($request->per_page)->withQueryString();

      return $this->apiResponse($officers);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'name' => 'required|string',
         'email' => 'required|email|unique:users,email',
         'employee_number' => 'required|string|unique:supervisors,employee_number',
         'password' => 'required|min:8|string'
      ]);

      if ($validator['password'] !== $request->confirm_password) return $this->apiResponse(null, 'Confirm password does not match', 422);

      $officer = Officer::create([
         'employee_number' => $validator['employee_number']
      ]);

      $userController = new UserController;

      $userData = [
         'name' => $validator['name'],
         'email' => $validator['email'],
         'userable_type' => Officer::MORPH_ALIAS,
         'userable_id' => $officer->id,
         'password' => Hash::make($validator['password'])
      ];

      $user = $userController->create($userData)->user;

      return $this->apiResponse($user, 'New officer created successfully', 201);
   }

   public function update(Request $request, $id) {
      $officer = Officer::find($id);

      $validator = $request->validate([
         'name' => 'required|string',
         'email' => "required|email|unique:users,email,{$officer->user->id}",
         'employee_number' => "required|string|unique:officers,employee_number,{$officer->id}",
      ]);

      $officer->update([
         'employee_number' => $validator['employee_number']
      ]);

      $user = User::where('userable_id', $officer->id)->where('userable_type', Officer::MORPH_ALIAS)->update([
         'name' => $validator['name'],
         'email' => $validator['email']
      ]);

      return $this->apiResponse(true, 'Officer updated successfully');
   }

   public function delete(Request $request) {
      $request->validate([
         'id' => 'required'
      ]);

      $officer = Officer::find($request->id);
      if (!$officer) return $this->apiResponse(null, 'Officer not found', 422);

      $officer->delete();
      User::where('userable_type', Officer::MORPH_ALIAS)->where('userable_id', $request->id)->delete();

      return $this->apiResponse(true, 'Officer has been deleted');
   }
}
