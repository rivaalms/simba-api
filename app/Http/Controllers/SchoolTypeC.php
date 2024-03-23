<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolTypeReq;
use App\Models\SchoolType;
use Illuminate\Http\Request;

class SchoolTypeC extends Controller
{
   public function get(Request $request) {
      $types = SchoolType::filter(request(['search']))
         ->latest()
         ->paginate($request->per_page)
         ->withQueryString();
      return parent::apiResponse($types);
   }

   public function create(SchoolTypeReq $request) {
      $_type = $request->validated();
      $type = SchoolType::create($_type);
      return parent::apiResponse($type, 'Tipe sekolah berhasil ditambahkan');
   }

   public function update(SchoolTypeReq $request, int $id) {
      $_type = $request->validated();
      SchoolType::find($id)->update($_type);
      return parent::apiResponse(true, 'Tipe sekolah berhasil diperbarui');
   }

   public function delete(int $id) {
      SchoolType::find($id)->delete();
      return parent::apiResponse(true, 'Tipe sekolah berhasil dihapus');
   }
}
