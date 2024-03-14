<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
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
}
