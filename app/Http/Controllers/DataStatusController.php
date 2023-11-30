<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormDataStatusRequest;
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

   public function create(FormDataStatusRequest $request) {
      $_status = $request->validated();
      $status = DataStatus::create($_status);
      return $this->apiResponse($status, 'Status data berhasil dibuat', 201);
   }

   public function update(FormDataStatusRequest $request, int $id) {
      $_status = $request->validated();
      DataStatus::find($id)->update($_status);
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
