<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormSchoolRequest;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SchoolController extends Controller
{
   public function get(Request $request)
   {
      $schools = School::search($request->search)->type($request->type)->supervisor($request->supervisor)->latest()->paginate($request->per_page)->withQueryString();
      return $this->apiResponse($schools);
   }

   public function getDetails(Request $request, int $id)
   {
      $school = School::find($id);
      return $this->apiResponse($school);
   }

   public function create(FormSchoolRequest $request)
   {
      $_user = $request->safe()->only(User::USER_FIELDS);
      $_school = $request->safe()->except(array_keys($_user));

      if ($_user['password'] !== $request->confirm_password) return $this->apiResponse(null, 'Konfirmasi kata sandi tidak sesuai', 422);

      $school = School::create($_school);

      $userC = new UserController;

      $_user['userable_type'] = School::MORPH_ALIAS;
      $_user['userable_id'] = $school->id;
      $_user['password'] = Hash::make($_user['password']);

      $user = $userC->create($_user)->user;

      return $this->apiResponse($user, 'Sekolah berhasil dibuat', 201);
   }

   public function update(FormSchoolRequest $request, int $id)
   {
      $_user = $request->safe()->only(User::USER_FIELDS);
      $_school = $request->safe()->except(array_keys($_user));

      School::find($id)->update($_school);
      User::where('userable_id', $id)->where('userable_type', School::MORPH_ALIAS)->update($_user);

      return $this->apiResponse(true, 'Sekolah berhasil diperbarui');
   }

   public function delete(int $id)
   {
      School::find($id)->delete();
      (new UserController())->delete($id, School::MORPH_ALIAS);
      return $this->apiResponse(true, 'Sekolah berhasil dihapus');
   }

   public function getOptions()
   {
      $schools = School::select('id')->supervisor(request('supervisor'))->get();
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
