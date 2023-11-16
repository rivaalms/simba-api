<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupervisorController extends Controller
{
   public function get(Request $request) {
      $supervisors = Supervisor::filter(request(['search']))->latest()->paginate($request->per_page)->withQueryString();

      return $this->apiResponse($supervisors);
   }

   public function getDetails(Request $request, int $id) {
      $supervisor = Supervisor::with(['schools' => function (HasMany $query) {
         $query->without('supervisor');
      }])->find($id);

      return $this->apiResponse($supervisor);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'name' => 'required|string',
         'email' => 'required|email|unique:users,email',
         'employee_number' => 'required|string|unique:supervisors,employee_number',
         'password' => 'required|min:8|string'
      ]);

      if ($validator['password'] !== $request->confirm_password) return $this->apiResponse(null, 'Confirm password does not match', 422);

      $supervisor = Supervisor::create([
         'employee_number' => $validator['employee_number']
      ]);

      $userController = new UserController;

      $userData = [
         'name' => $validator['name'],
         'email' => $validator['email'],
         'userable_type' => Supervisor::MORPH_ALIAS,
         'userable_id' => $supervisor->id,
         'password' => Hash::make($validator['password'])
      ];

      $user = $userController->create($userData)->user;

      return $this->apiResponse($user, 'New supervisor created successfully', 201);
   }

   public function update(Request $request, int $id) {
      $supervisor = Supervisor::find($id);

      $validator = $request->validate([
         'name' => 'required|string',
         'email' => "required|email|unique:users,email,{$supervisor->user->id}",
         'employee_number' => "required|string|unique:supervisors,employee_number,{$supervisor->id}",
      ]);

      $supervisor->update([
         'employee_number' => $validator['employee_number']
      ]);

      $user = User::where('userable_id', $id)->where('userable_type', Supervisor::MORPH_ALIAS)->update([
         'name' => $validator['name'],
         'email' => $validator['email']
      ]);

      return $this->apiResponse(true, 'Supervisor updated successfully');
   }

   public function delete(Request $request) {
      $request->validate([
         'id' => 'required'
      ]);

      $supervisor = Supervisor::find($request->id);

      if (!$supervisor) return $this->apiResponse(null, 'Supervisor not found', 422);

      $supervisor->delete();
      User::where('userable_type', Supervisor::MORPH_ALIAS)->where('userable_id', $request->id)->delete();

      return $this->apiResponse(true, 'Supervisor has been deleted');
   }

   public function getOptions() {
      $supervisors = Supervisor::select('id')->distinct('id')->get();
      $data = [];

      foreach ($supervisors as $s) {
         array_push($data, [
            'label' => $s->user->name,
            'value' => $s->id
         ]);
      }

      return $this->apiResponse($data);
   }
}
