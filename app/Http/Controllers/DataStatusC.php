<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataStatusReq;
use App\Models\DataStatus;
use Illuminate\Http\Request;

class DataStatusC extends Controller
{
   public function get(Request $request) {
      $status = DataStatus::filter(request(['search']))
         ->latest()
         ->paginate($request->per_page)
         ->withQueryString();
      return parent::apiResponse($status);
   }

   public function create(DataStatusReq $request) {
      $_status = $request->validated();
      $status = DataStatus::create($_status);
      return parent::apiResponse($status, 'Status data berhasil ditambahkan');
   }

   public function update(DataStatusReq $request, int $id) {
      $_status = $request->validated();
      DataStatus::find($id)->update($_status);
      return parent::apiResponse(null, 'Status data berhasil diperbarui');
   }

   public function delete(int $id) {
      DataStatus::find($id)->delete();
      return parent::apiResponse(null, 'Status data berhasil dihapus');
   }
}
