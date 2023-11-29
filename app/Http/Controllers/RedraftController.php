<?php

namespace App\Http\Controllers;

use App\Models\Redraft;
use Illuminate\Http\Request;

class RedraftController extends Controller
{
   public function get(Request $request, int $data_id) {
      $redrafts = Redraft::where('data_id', $data_id)->latest()->get();
      // $data = Redraft::mapRedrafts($redrafts);
      return $this->apiResponse($redrafts);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'user_id' => 'required|numeric',
         'data_id' => 'required|numeric',
         'message' => 'required'
      ]);

      $redraft = Redraft::create($validator);
      return $this->apiResponse($redraft, 'Pesan berhasil dikirim');
   }

   public function update(Request $request, int $id) {
      $redraft = Redraft::find($id);

      $validator = $request->validate([
         'user_id' => 'required|numeric',
         'data_id' => 'required|numeric',
         'message' => 'required'
      ]);

      $redraft->update($validator);
      return $this->apiResponse(true, 'Pesan berhasil diperbarui');
   }

   public function delete(Request $request, int $id) {
      Redraft::find($id)->delete();
      return $this->apiResponse(true, 'Pesan berhasil dihapus');
   }
}
