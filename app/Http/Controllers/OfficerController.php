<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormSupervisorRequest;
use App\Models\Officer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OfficerController extends Controller
{
   public function get(Request $request)
   {
      $officers = Officer::filter(request(['search']))->latest()->paginate($request->per_page)->withQueryString();
      return $this->apiResponse($officers);
   }

   public function getDetails(Request $request, int $id)
   {
      $officer = Officer::find($id);

      return $this->apiResponse($officer);
   }

   public function create(FormSupervisorRequest $request)
   {
      $_user = $request->safe()->only(User::USER_FIELDS);
      $_officer = $request->safe()->except(array_keys($_user));

      if ($_user['password'] !== $request->confirm_password) return $this->apiResponse(null, 'Konfirmasi kata sandi tidak sesuai', 422);

      $officer = Officer::create($_officer);
      $_user['userable_type'] = Officer::MORPH_ALIAS;
      $_user['userable_id'] = $officer->id;

      $userC = new UserController;
      $user = $userC->create($_user)->user;

      return $this->apiResponse($user, 'Officer baru berhasil dibuat');
   }

   public function update(Request $request, $id)
   {
      $_user = $request->safe()->only(User::USER_FIELDS);
      $_officer = $request->safe()->except(array_keys($_user));

      Officer::find($id)->update($_officer);
      User::where('userable_type', Officer::MORPH_ALIAS)->where('userable_id', $id)->update($_user);
      return $this->apiResponse(true, 'Officer berhasil diperbarui');
   }

   public function delete(int $id)
   {
      Officer::find($id)->delete();
      (new UserController())->delete($id, Officer::MORPH_ALIAS);
      return $this->apiResponse(true, 'Officer berhasil dihapus');
   }
}
