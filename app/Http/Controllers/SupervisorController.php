<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormSupervisorRequest;
use App\Models\User;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupervisorController extends Controller
{
   public function get(Request $request)
   {
      $supervisors = Supervisor::filter(request(['search']))->latest()->paginate($request->per_page)->withQueryString();
      return $this->apiResponse($supervisors);
   }

   public function getDetails(Request $request, int $id)
   {
      $user = $request->user();
      if ($user->userable_type == 'supervisor' && $id != $user->userable_type) {
         return $this->apiResponse(null, 'Aksi dilarang', 403);
      }

      $supervisor = Supervisor::with(['schools' => function (HasMany $query) {
         $query->without('supervisor');
      }])->find($id);

      return $this->apiResponse($supervisor);
   }

   public function create(FormSupervisorRequest $request)
   {
      $_user = $request->safe()->only(User::USER_FIELDS);
      $_supervisor = $request->safe()->except(array_keys($_user));

      if ($_user['password'] !== $request->confirm_password) return $this->apiResponse(null, 'Konfirmasi kata sandi tidak sesuai', 422);

      $supervisor = Supervisor::create($_supervisor);

      $_user['userable_type'] = Supervisor::MORPH_ALIAS;
      $_user['userable_id'] = $supervisor->id;

      $userC = new UserController;

      $user = $userC->create($_user)->user;
      return $this->apiResponse($user, 'Pengawas berhasil dibuat');
   }

   public function update(FormSupervisorRequest $request, int $id)
   {
      $_user = $request->safe()->only(User::USER_FIELDS);
      $_supervisor = $request->safe()->except(array_keys($_user));

      Supervisor::find($id)->update($_supervisor);
      User::where('userable_id', $id)->where('userable_type', Supervisor::MORPH_ALIAS)->update($_user);
      return $this->apiResponse(true, 'Pengawas berhasil diperbarui');
   }

   public function delete(int $id)
   {
      Supervisor::find($id)->delete();
      (new UserController())->delete($id, Supervisor::MORPH_ALIAS);
      return $this->apiResponse(true, 'Pengawas berhasil dihapus');
   }

   public function getOptions()
   {
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
