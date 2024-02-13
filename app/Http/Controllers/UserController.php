<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\Comment;
use App\Models\Officer;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\FormUserRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserController extends Controller
{
   public function login(Request $request)
   {
      $validator = Validator::make($request->only('email', 'password'), [
         'email' => 'required|email',
         'password' => 'required'
      ]);

      if ($validator->fails()) return $this->apiResponse(null, 'Format kredensial tidak sesuai', 422);

      try {
         $user = User::where('email', $request->email)->firstOr(
            throw new Exception('Kredensial Anda tidak sesuai dengan arsip kami', 401)
         );

         if (!Hash::check($request->password, $user->password)) {
            throw new Exception('Kredensial Anda tidak sesuai dengan arsip kami', 401);
         }

         $requestOrigin = $request->header('Origin');

         if ($user->userable_type) {
            $envPointer = strtoupper($user->userable_type);
            $allowedUrl = env("CLIENT_{$envPointer}_URL");
         } else {
            $allowedUrl = env("CLIENT_ADMIN_URL");
         }

         if ($requestOrigin !== $allowedUrl) {
            throw new Exception('Akses ditolak', 403);
         }

         if ($user->userable_type) {
            $user->load('userable');
            $tokenAbility = $user->userable_type;
         } else {
            $tokenAbility = '*';
         }

         $token = $user->createToken('auth_token', [$tokenAbility])->plainTextToken;

         $data = compact('user', 'token');
      } catch (Exception $e) {
         return $this->apiResponse(null, $e->getMessage(), $e->getCode());
      }

      return $this->apiResponse($data, 'Login berhasil');
   }

   public function logout()
   {
      request()->user()->currentAccessToken()->delete();
      return $this->apiResponse(null, 'Logout berhasil');
   }

   public function create(array $data)
   {
      $user = User::create($data);
      $user->load(['userable' => function (MorphTo $morphTo) {
         $morphTo->constrain([
            School::class => function ($query) {
               $query->without('user');
            },
            Supervisor::class => function ($query) {
               $query->without('user');
            },
            Officer::class => function ($query) {
               $query->without('user');
            }
         ]);
      }]);

      return parent::jsonify([
         'success' => true,
         'user' => $user
      ]);
   }

   public function get(Request $request)
   {
      $users = User::filter(request(['search']))->with(['userable' => function (MorphTo $morphTo) {
         $morphTo->constrain([
            School::class => function ($query) {
               $query->without('user');
            },
            Supervisor::class => function ($query) {
               $query->without('user');
            },
            Officer::class => function ($query) {
               $query->without('user');
            }
         ]);
      }])->get();
      return $this->apiResponse($users);
   }

   public function delete(int $id, String $userable)
   {
      $userQuery = User::where('userable_type', $userable)->where('userable_id', $id);
      $user = $userQuery->first();
      Comment::where('user_id', $user->id)->delete();
      $userQuery->delete();
      return true;
   }

   public function update(FormUserRequest $request, int $id)
   {
      $currentUser = $request->user();

      if ($id !== $currentUser->id) return $this->apiResponse(null, 'Pengguna tidak sama', 403);

      $_user = $request->safe()->except(['password']);
      $_password = $request->safe()->only('password')['password'];

      if (!Hash::check($_password, $currentUser->password)) return $this->apiResponse(null, 'Kata sandi salah', 422);

      if ($currentUser->userable_type) {
         $userableRequest = $request->except(['name', 'email', 'password', 'supervisor_id']);

         switch ($currentUser->userable_type) {
            case 'school':
               School::find($currentUser->userable_id)->update($userableRequest);
               break;
            case 'supervisor':
               Supervisor::find($currentUser->userable_id)->update($userableRequest);
               break;
            case 'officer':
               Officer::find($currentUser->userable_id)->update($userableRequest);
               break;
            default:
               break;
         }
      }

      $currentUser->update($_user);
      return $this->apiResponse($currentUser->load(['userable']), 'Pengguna berhasil diperbarui');
   }

   public function forgotPassword(Request $request)
   {
      $request->validate([
         'email' => 'required|email|exists:users,email',
      ]);

      $status = Password::sendResetLink($request->only('email'));

      if ($status === Password::RESET_LINK_SENT) {
         return $this->apiResponse(true, 'Reset password link sent to your email');
      } else {
         return $this->apiResponse(false, 'Failed to send reset password link', 422);
      }
   }

   public function resetPassword(Request $request)
   {
      $request->validate([
         'token' => 'required',
         'email' => 'required|email',
         'password' => 'required|min:8|confirmed',
      ]);

      $status = Password::reset(
         $request->only('email', 'password', 'password_confirmation', 'token'),
         function (User $user, string $password) {
            $user->forceFill([
               'password' => Hash::make($password)
            ]);

            $user->save();

            event(new PasswordReset($user));
         }
      );

      $expectsJSON = $request->header('Accept') === 'application/json';

      if ($status === Password::PASSWORD_RESET) {
         if ($expectsJSON) return $this->apiResponse(true, 'Kata sandi berhasil diatur ulang');
         return redirect('/');
      }

      if ($expectsJSON) return $this->apiResponse(false, 'Kata sandi gagal diatur ulang', 500);
      dd('failed');
   }

   public function activate(int $id)
   {
      $user = User::find($id);
      if ($user->status === User::USER_STATUS['active']) return $this->apiResponse(false, 'Pengguna sudah aktif', 422);

      $user->update(['status' => 'ACTIVE']);

      return $this->apiResponse(true, 'Pengguna berhasil diaktifkan');
   }

   public function inactivate(int $id)
   {
      $user = User::find($id);
      if ($user->status === User::USER_STATUS['inactive']) return $this->apiResponse(false, 'Pengguna sudah nonaktif', 422);

      $user->update(['status' => 'INACTIVE']);

      return $this->apiResponse(true, 'Pengguna berhasil dinonaktifkan');
   }

   public function count()
   {
      $query = User::all();
      $total = $query->count();
      $user_by_type = [
         School::MORPH_ALIAS => 0,
         Supervisor::MORPH_ALIAS => 0,
         Officer::MORPH_ALIAS => 0,
         'admin' => 0
      ];

      foreach ($user_by_type as $key => $val) {
         if ($key === 'admin') $user_by_type[$key] = $query->where('userable_type', null)->count();
         else $user_by_type[$key] = $query->where('userable_type', $key)->count();
      }

      return $this->apiResponse(compact('total', 'user_by_type'));
   }
}
