<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
   use AuthorizesRequests, ValidatesRequests;

   public function apiResponse($data, $message = null, $status = 200)
   {
      $success = ($status > 199 && $status < 300) ? true : false;

      return response()->json(compact('success', 'message', 'data'), $status);
   }

   public function jsonify($data)
   {
      return json_decode(json_encode($data));
   }
}
