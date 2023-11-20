<?php

namespace App\Http\Controllers;

use App\Models\DataType;
use Illuminate\Http\Request;

class DataTypeController extends Controller
{
   public function get(Request $request) {
      $types = DataType::filter(request(['search', 'category']))->get();
      return $this->apiResponse($types);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'name' => 'required|unique:data_types,name',
         'slug' => 'required|unique:data_types,slug',
         'data_category_id' => 'required|numeric|exists:data_categories,id'
      ]);

      $type = DataType::create($validator);
      return $this->apiResponse($type, 'Tipe data baru berhasil dibuat');
   }

   public function update(Request $request, int $id) {
      $type = DataType::find($id);

      $validator = $request->validate([
         'name' => 'required',
         'slug' => 'required',
         'data_category_id' => 'required|numeric'
      ]);

      $type->update($validator);
      return $this->apiResponse(true, 'Tipe data berhasil diperbarui');
   }

   public function delete(Request $request) {
      $validator = $request->validate([
         'id' => 'required'
      ]);

      DataType::find($validator['id'])->delete();
      return $this->apiResponse(true, 'Tipe data berhasil dihapus');
   }

   public function getOptions(Request $request) {
      $types = DataType::select('name as label', 'id as value')->where('data_category_id', $request->category)->get();
      return $this->apiResponse($types);
   }
}
