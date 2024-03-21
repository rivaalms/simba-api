<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupervisorReq;
use App\Models\Supervisor;
use App\Models\User;
use App\Traits\CheckUserable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class SupervisorC extends Controller
{
   use CheckUserable;

   public function get(Request $request) {
      $supervisors = Supervisor::filter(request(['search']))
         ->latest()->paginate($request->per_page)
         ->withQueryString();
      return parent::apiResponse($supervisors);
   }

   public function show(Request $request, int $id) {
      $user = $request->user();
      if ($this->isSupervisor($user) && $id != $user->userable_type) {
         return parent::apiResponse(null, 'Aksi tidak diizinkan', 403);
      }

      $supervisor = Supervisor::with(['schools' => fn (HasMany $query) =>
         $query->without('supervisor')
      ])
      ->find($id);

      return parent::apiResponse($supervisor);
   }

   public function create(SupervisorReq $request) {
      $_user = $request->safe()->only(User::USER_FIELDS);
      $_supervisor = $request->safe()->except(array_keys($_user));

      if ($_user['password'] != $request->confirm_password) {
         return parent::apiResponse(null, 'Konfirmasi kata sandi tidak cocok', 422);
      }

      $supervisor = Supervisor::create($_supervisor);
      $_user['userable_type'] = Supervisor::MORPH_ALIAS;
      $_user['userable_id'] = $supervisor->id;

      $userC = new UserC;
      $user = $userC->create($_user)->user;
      return parent::apiResponse($user, 'Pengawas berhasil ditambahkan');
   }

   public function update(SupervisorReq $request, int $id) {
      $_user = $request->safe()->only(User::USER_FIELDS);
      $_supervisor = $request->safe()->except(array_keys($_user));

      $supervisor = Supervisor::find($id);
      $supervisor->update($_supervisor);
      $supervisor->user->update($_user);

      return parent::apiResponse(true, 'Pengawas berhasil diperbarui');
   }

   public function delete(int $id) {
      Supervisor::find($id)->delete();
      (new UserC)->delete($id, Supervisor::MORPH_ALIAS);
      return parent::apiResponse(true, 'Pengawas berhasil dihapus');
   }
}
