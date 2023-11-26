<?php

namespace App\Http\Controllers;

use App\Models\DataType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DataTypeController extends Controller
{
   public function get(Request $request) {
      $types = DataType::filter(request(['search', 'category']))->latest()->paginate($request->per_page)->withQueryString();
      return $this->apiResponse($types);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'name' => 'required|unique:data_types,name',
         'data_category_id' => 'required|numeric|exists:data_categories,id'
      ]);
      $validator['slug'] = parent::generateSlug($validator['name']);

      $type = DataType::create($validator);
      return $this->apiResponse($type, 'Tipe data baru berhasil dibuat');
   }

   public function update(Request $request, int $id) {
      $type = DataType::find($id);

      $validator = $request->validate([
         'name' => [ 'required', Rule::unique('data_types', 'name')->ignore($id) ],
         'data_category_id' => 'required|numeric'
      ]);
      $validator['slug'] = parent::generateSlug($validator['name']);

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
