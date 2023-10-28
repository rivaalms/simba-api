<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   public function login(Request $request) {
      $validator = Validator::make($request->only('email', 'password'), [
         'email' => 'required|email',
         'password' => 'required'
      ]);

      if ($validator->fails()) return $this->apiResponse(null, 'Invalid credentials format', 422);

      try {
         $user = User::where('email', $request->email)->first();
         if (!Hash::check($request->password, $user->password)) throw new Exception();
         if ($request->header('UserableType')) $user->userable;
         $token = $user->createToken('auth_token')->plainTextToken;
         $data = compact('user', 'token');
      } catch (Exception $e) {
         return $this->apiResponse(null, 'Your credentials does not match our records', 401);
      }

      return $this->apiResponse($data);
   }

   public function logout() {
      Auth::user()->tokens()->delete();
      return $this->apiResponse(null);
   }
}
