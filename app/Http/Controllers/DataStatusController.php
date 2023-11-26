<?php

namespace App\Http\Controllers;

use App\Models\DataStatus;
use Illuminate\Http\Request;

class DataStatusController extends Controller
{
   public function get(Request $request) {
      $query = DataStatus::filter(request(['search']))->latest();
      if ($request->per_page > 0) $status = $query->paginate($request->per_page)->withQueryString();
      else $status = $query->get();
      return $this->apiResponse($status);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'name' => 'required|unique:data_statuses,name'
      ]);

      $status = DataStatus::create([
         'name' => $validator['name']
      ]);

      return $this->apiResponse($status, 'Status data berhasil dibuat', 201);
   }

   public function update(Request $request, int $id) {
      $status = DataStatus::find($id);

      $validator = $request->validate([
         'name' => 'required'
      ]);

      $status->update([
         'name' => $validator['name']
      ]);

      return $this->apiResponse(true, 'Status data berhasil diperbarui');
   }

   public function delete(Request $request) {
      $validator = $request->validate([
         'id' => 'required'
      ]);

      DataStatus::find($validator['id'])->delete();
      return $this->apiResponse(true, 'Status data berhasil dihapus');
   }

   public function getOptions() {
      $status = DataStatus::select('name as label', 'id as value')->get();
      return $this->apiResponse($status);
   }
}
