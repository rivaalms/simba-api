<?php

namespace App\Http\Controllers;

use App\Models\DataCategory;
use Illuminate\Http\Request;

class DataCategoryController extends Controller
{
   public function get(Request $request) {
      $categories = DataCategory::filter(request(['search']))->get();
      return $this->apiResponse($categories);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'name' => 'required|unique:data_categories,name',
         'slug' => 'required|unique:data_categories,slug',
      ]);

      $category = DataCategory::create($validator);
      return $this->apiResponse($category, 'Katagori data berhasil dibuat');
   }

   public function update(Request $request, int $id) {
      $category = DataCategory::find($id);

      $validator = $request->validate([
         'name' => 'required',
         'slug' => 'required'
      ]);

      $category->update($validator);

      return $this->apiResponse(true, 'Kategori data berhasil diperbarui');
   }

   public function delete(Request $request) {
      $validator = $request->validate([
         'id' => 'required'
      ]);

      DataCategory::find($validator['id'])->delete();
      return $this->apiResponse(true, 'Kategori data berhasil dihapus');
   }

   public function getOptions() {
      $categories = DataCategory::select('name as label', 'id as value')->get()->toArray();
      return $this->apiResponse($categories);
   }
}
