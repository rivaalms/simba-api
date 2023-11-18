<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Exception;
use App\Models\User;
use App\Models\School;
use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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

      if ($validator->fails()) return $this->apiResponse(null, 'Format kredensial tidak sesuai', 422);

      try {
         $user = User::where('email', $request->email)->firstOrFail();
         if (!Hash::check($request->password, $user->password)) throw new Exception();
         if ($request->header('UserableType')) $user->userable;
         $token = $user->createToken('auth_token')->plainTextToken;
         $data = compact('user', 'token');
      } catch (Exception $e) {
         return $this->apiResponse(null, 'Kredensial Anda tidak sesuai dengan arsip kami', 401);
      }

      return $this->apiResponse($data, 'Login berhasil');
   }

   public function logout() {
      Auth::user()->tokens()->delete();
      return $this->apiResponse(null, 'Logout berhasil');
   }

   public function create(Array $data) {
      $validator = Validator::make($data, [
         'name' => 'required|string',
         'email' => 'required|email|unique:users,email',
         'password' => 'required|min:8|string',
         'userable_type' => 'nullable|string',
         'userable_id' => 'nullable|integer|numeric'
      ]);

      if ($validator->fails()) return parent::jsonify([
         'success' => false,
         'message' => $validator->errors()
      ]);

      $user = User::create($validator->validated());

      return parent::jsonify([
         'success' => true,
         'user' => $user->load(['userable'])
      ]);
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
         if ($key === 'admin') $user_by_type[$key] = $query->where('userable_type', null)->count();
         else $user_by_type[$key] = $query->where('userable_type', $key)->count();
      }

      return $this->apiResponse(compact('total', 'user_by_type'));
   }
}
