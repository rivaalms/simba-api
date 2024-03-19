<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use App\Models\SchoolType;
use Illuminate\Http\Request;
use App\Traits\CheckUserable;
use App\Http\Requests\SchoolReq;
use Illuminate\Support\Facades\Hash;

class SchoolC extends Controller
{
   use CheckUserable;

   public function get(Request $request) {
      $user = $request->user();
      $req = $request->all();

      if ($this->isSupervisor($user)) {
         $req['supervisor'] = $user->userable_id;
      }

      $schools = School::search($request->search)
         ->latest()
         ->type($req['type'] ?? null)
         ->supervisor($req['supervisor'] ?? null)
         ->paginate($request->per_page)
         ->withQueryString();

      return parent::apiResponse($schools);
   }

   public function show(Request $request, int $id) {
      $user = $request->user();
      $school = School::find($id);

      if ($this->isSupervisor($user) && $school->supervisor_id != $user->userable_id) {
         return parent::apiResponse(null, 'Aksi tidak diizinkan', 403);
      }

      return parent::apiResponse($school);
   }

   public function create(SchoolReq $request) {
      $_user = $request->safe()->only(User::USER_FIELDS);
      $_school = $request->safe()->except(array_keys($_user));

      if ($_user['password'] != $request->confirm_password) {
         return parent::apiResponse(null, 'Kata sandi tidak cocok', 422);
      }

      $school = School::create($_school);
      $userC = new UserC;

      $_user['userable_type'] = School::MORPH_ALIAS;
      $_user['userable_id'] = $school->id;
      $_user['password'] = Hash::make($_user['password']);

      $user = $userC->create($_user)->user;
      return parent::apiResponse($user, 'Sekolah berhasil ditambahkan');
   }

   public function update(SchoolReq $request, int $id) {
      $_user = $request->safe()->only(User::USER_FIELDS);
      $_school = $request->safe()->except(array_keys($_user));

      $school = School::find($id);
      $school->update($_school);
      $school->user->update($_user);

      return parent::apiResponse(true, 'Sekolah berhasil diperbarui');
   }

   public function delete(int $id) {
      School::find($id)->delete();
      $userC = new UserC;
      $userC->delete($id, School::MORPH_ALIAS);
      return parent::apiResponse(true, 'Sekolah berhasil dihapus');
   }

   public function count(Request $request) {
      $user = $request->user();
      $query = School::select('school_type_id');
      $byTypeQuery = School::without(['user', 'type', 'supervisor'])->selectRaw('count(schools.id) as count, school_types.name as type')->join('school_types', 'schools.school_type_id', '=', 'school_types.id');


      if ($user->userable_type == 'supervisor') {
         $query = $query->where('supervisor_id', $user->userable_id);
         $byTypeQuery = $byTypeQuery->where('supervisor_id', $user->userable_id);
      }

      $count = $query->count();
      $countByType = $byTypeQuery->groupBy('school_types.name')->get();

      $result = array(
         'total' => $count,
         'by_type' => $countByType
      );

      return parent::apiResponse($result);
   }
}
