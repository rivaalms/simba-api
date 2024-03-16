<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserReq;
use App\Models\Comment;
use App\Models\User;
use App\Models\School;
use App\Models\Officer;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Hash;
use App\Traits\CheckUserable;

class UserC extends Controller
{
   use CheckUserable;

   public function get(Request $request) {
      $users = User::filter(request(['search']))
         ->with(['userable' => function (MorphTo $morphTo) {
            $morphTo->constrain([
               School::class => function (Builder $query) {
                  $query->without('user');
               },
               Supervisor::class => function (Builder $query) {
                  $query->without('user');
               },
               Officer::class => function (Builder $query) {
                  $query->without('user');
               }
            ]);
         }])
         ->get();

      return parent::apiResponse($users);
   }

   public function create(Array $data) {
      $user = User::create($data);
      $user->load(['userable' => function (MorphTo $morphTo) {
         $morphTo->constrain([
            School::class => function (Builder $query) {
               $query->without('user');
            },
            Supervisor::class => function (Builder $query) {
               $query->without('user');
            },
            Officer::class => function (Builder $query) {
               $query->without('user');
            }
         ]);
      }]);

      return json_decode(json_encode([
         'success' => true,
         'user' => $user
      ]));
   }

   public function update(UserReq $request, int $id) {
      $currentUser = $request->user();

      if ($id != $currentUser->id) {
         return parent::apiResponse(null, 'Pengguna tidak sama', 403);
      }

      $_user = $request->safe()->except(['password']);
      $_password = $request->safe()->only('password')['password'];

      if (!Hash::check($_password, $currentUser->password)) {
         return parent::apiResponse(null, 'Kata sandi salah', 422);
      }

      if ($this->isNotAdmin($currentUser)) {
         $userableReq = $request->except(['name', 'email', 'password', 'supervisor_id']);
         $userableId = $currentUser->userable_id;

         switch ($currentUser->userable_type) {
            case School::MORPH_ALIAS:
               School::find($userableId)->update($userableReq);
               break;
            case Supervisor::MORPH_ALIAS:
               Supervisor::find($userableId)->update($userableReq);
               break;
            case Officer::MORPH_ALIAS:
               Officer::find($userableId)->update($userableReq);
               break;
            default: break;
         }
      }

      $currentUser->update($_user);
      return parent::apiResponse($currentUser->load(['userable']), 'Pengguna berhasil diperbarui');
   }

   public function delete(int $id, String $userable) {
      $userQuery = User::where('userable_type', $userable)
         ->where('userable_id', $id);

      $user = $userQuery->first();

      Comment::where('user_id', $user->id)->delete();
      $userQuery->delete();
      return true;
   }

   public function activate(int $id) {
      $user = User::find($id);
      if ($user->status == User::USER_STATUS['active']) {
         return parent::apiResponse(false, 'Pengguna sudah aktif', 422);
      }
      $user->update(['status' => User::USER_STATUS['active']]);
      return parent::apiResponse(true, 'Pengguna berhasil diaktifkan');
   }

   public function inactivate(int $id) {
      $user = User::find($id);
      if ($user->status == User::USER_STATUS['inactive']) {
         return parent::apiResponse(false, 'Pengguna sudah tidak aktif', 422);
      }
      $user->update(['status' => User::USER_STATUS['inactive']]);
      return parent::apiResponse(true, 'Pengguna berhasil dinonaktifkan');
   }

   public function count() {
      $query = User::all();
      $total = $query->count();
      $user_by_type = [
         School::MORPH_ALIAS => 0,
         Supervisor::MORPH_ALIAS => 0,
         Officer::MORPH_ALIAS => 0,
         'admin' => 0
      ];

      foreach ($user_by_type as $key => $val) {
         if ($key === 'admin') {
            $user_by_type[$key] = $query->where('userable_type', null)->count();
         } else {
            $user_by_type[$key] = $query->where('userable_type', $key)->count();
         }
      }

      return parent::apiResponse(compact('total', 'user_by_type'));
   }
}
