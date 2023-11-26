<?php

namespace App\Http\Controllers;

use App\Models\SchoolType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolTypeController extends Controller
{
   public function get(Request $request) {
      $query = SchoolType::filter(request(['search']))->latest();
      if ($request->per_page > 0) $types = $query->paginate($request->per_page)->withQueryString();
      else $types = $query->get();
      return $this->apiResponse($types);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'name' => 'required|unique:school_types,name'
      ]);

      $type = SchoolType::create($validator);
      return $this->apiResponse($type, 'Tipe sekolah baru berhasil dibuat');
   }

   public function update(Request $request, int $id) {
      $type = SchoolType::find($id);

      $validator = $request->validate([
         'name' => ['required', Rule::unique('school_types', 'name')->ignore($type->id)]
      ]);

      $type->update($validator);
      return $this->apiResponse(true, 'Tipe sekolah berhasil diperbarui');
   }

   public function delete(Request $request) {
      $validator = $request->validate([
         'id' => 'required'
      ]);

      SchoolType::find($validator['id'])->delete();
      return $this->apiResponse(true, 'Tipe sekolah berhasil dihapus');
   }

   public function getOptions() {
      $data = SchoolType::select('name as label', 'id as value')->distinct('id')->get()->toArray();
      return $this->apiResponse($data);
   }
}
