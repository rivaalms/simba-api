<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReligionController extends Controller
{
   public function get(Request $request) {
      $religions = Religion::filter(request(['search']))->get();
      return $this->apiResponse($religions);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'name' => 'required|unique:religions,name'
      ]);
      $religion = Religion::create($validator);
      return $this->apiResponse($religion, 'Data agama baru berhasil dibuat');
   }

   public function update(Request $request, int $id) {
      $religion = Religion::find($id);

      $validator = $request->validate([
         'name' => [ 'required', Rule::unique('religions', 'name')->ignore($religion->id) ]
      ]);

      $religion->update($validator);
      return $this->apiResponse(true, 'Data agama berhasil diperbarui');
   }

   public function delete(Request $request) {
      $validator = $request->validate([
         'id' => 'required'
      ]);
      Religion::find($validator['id'])->delete();
      return $this->apiResponse(true, 'Data agama berhasil dihapus');
   }
}
