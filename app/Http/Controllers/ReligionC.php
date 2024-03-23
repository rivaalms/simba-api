<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReligionReq;
use App\Models\Religion;
use Illuminate\Http\Request;

class ReligionC extends Controller
{
   public function get(Request $request) {
      $religions = Religion::filter(request(['search']))
         ->latest()
         ->paginate($request->per_page)
         ->withQueryString();
      return parent::apiResponse($religions);
   }

   public function create(ReligionReq $request) {
      $_religion = $request->validated();
      $religion = Religion::create($_religion);
      return parent::apiResponse($religion, 'Data agama berhasil ditambahkan');
   }

   public function update(ReligionReq $request, int $id) {
      $_religion = $request->validated();
      Religion::find($id)->update($_religion);
      return parent::apiResponse(true, 'Data agama berhasil diperbarui');
   }

   public function delete(int $id) {
      Religion::find($id)->delete();
      return parent::apiResponse(true, 'Data agama berhasil dihapus');
   }
}
