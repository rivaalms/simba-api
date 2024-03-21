<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataTypeReq;
use App\Models\DataType;
use Illuminate\Http\Request;

class DataTypeC extends Controller
{
   public function get(Request $request) {
      $types = DataType::filter(request(['search', 'category']))
         ->latest()
         ->paginate($request->per_page)
         ->withQueryString();
      return parent::apiResponse($types);
   }

   public function create(DataTypeReq $request) {
      $_type = $request->validated();
      $type = DataType::create($_type);
      return parent::apiResponse($type, 'Tipe data berhasil ditambahkan');
   }

   public function update(DataTypeReq $request, int $id) {
      $_type = $request->validated();
      DataType::find($id)->update($_type);
      return parent::apiResponse(true, 'Tipe berhasil diperbarui');
   }

   public function delete(int $id) {
      DataType::find($id)->delete();
      return parent::apiResponse(true, 'Tipe data berhasil dihapus');
   }
}
