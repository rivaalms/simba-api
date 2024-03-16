<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthC extends Controller
{
   public function login(Request $request) {
      $validator = Validator::make($request->only('email', 'password'), [
         'email' => 'required|email',
         'password' => 'required'
      ]);

      if ($validator->fails()) {
         return parent::apiResponse(null, 'Format kredensial tidak sesuai', 422);
      }

      try {
         $user = User::where('email', $request->email)->firstOr(function() {
            throw new Exception('Kredensial Anda tidak sesuai dengan arsip kami', 401);
         });

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
         return parent::apiResponse(null, $e->getMessage(), $e->getCode());
      }

      return $this->apiResponse($data, 'Login berhasil');
   }

   public function me(Request $request) {
      return parent::apiResponse($request->user());
   }

   public function logout() {
      request()->user()->currentAccessToken()->delete();
      return parent::apiResponse(null, 'Logout berhasil');
   }

   public function forgotPassword(Request $request) {
      $request->validate([
         'email' => 'required|email|exists:users,email',
      ]);

      $status = Password::sendResetLink($request->only('email'));

      if ($status !== Password::RESET_LINK_SENT) {
         return parent::apiResponse(false, 'Gagal mengirim link reset kata sandi', 500);
      }

      return parent::apiResponse(true, 'Link reset kata sandi berhasil dikirim ke email Anda');
   }

   public function resetPassword(Request $request) {
      $request->validate([
         'token' => 'required',
         'email' => 'required|email',
         'password' => 'required|min:8|confirmed'
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

      if ($status === Password::PASSWORD_RESET) {
         return parent::apiResponse(true, 'Kata sandi Anda berhasil diatur ulang');
      }

      return parent::apiResponse(false, 'Gagal memperbarui kata sandi', 500);
   }
}
