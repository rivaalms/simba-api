<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Illuminate\Http\Request;
use App\Traits\CheckUserable;
use App\Http\Requests\SupervisorReq as OfficerReq;
use App\Models\User;

class OfficerC extends Controller
{
   use CheckUserable;

   public function get(Request $request) {
      $officers = Officer::filter(request(['search']))
         ->latest()
         ->paginate($request->per_page)
         ->withQueryString();
      return parent::apiResponse($officers);
   }

   public function show(Request $request, int $id) {
      $user = $request->user();
      if ($this->isOfficer($user) && $id != $user->userable_id) {
         return parent::apiResponse(null, 'Aksi tidak diizinkan', 403);
      }

      $officer = Officer::find($id);
      return parent::apiResponse($officer);
   }

   public function create(OfficerReq $request) {
      $_user = $request->safe()->only(User::USER_FIELDS);
      $_officer = $request->safe()->except(array_keys($_user));

      if ($_user['password'] != $request->confirm_password) {
         return parent::apiResponse(null, 'Konfirmasi kata sandi tidak sesuai', 422);
      }

      $officer = Officer::create($_officer);
      $_user['userable_type'] = Officer::MORPH_ALIAS;
      $_user['userable_id'] = $officer->id;

      $userC = new UserC;
      $user = $userC->create($_user)->user;

      return parent::apiResponse($user, 'Officer berhasil ditambahkan');
   }

   public function update(OfficerReq $request, int $id) {
      $_user = $request->safe()->only(User::USER_FIELDS);
      $_officer = $request->safe()->except(array_keys($_user));

      $officer = Officer::find($id);
      $officer->update($_officer);
      $officer->user->update($_user);

      return parent::apiResponse(true, 'Officer berhasil diperbarui');
   }

   public function delete(int $id) {
      Officer::find($id)->delete();
      (new UserC)->delete($id, Officer::MORPH_ALIAS);
      return parent::apiResponse(true, 'Officer berhasil dihapus');
   }
}
