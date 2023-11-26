<?php

namespace App\Http\Controllers;

use App\Models\DataCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DataCategoryController extends Controller
{
   public function get(Request $request) {
      $query = DataCategory::filter(request(['search']))->latest();
      if ($request->per_page > 0) $categories = $query->paginate($request->per_page)->withQueryString();
      else $categories = $query->get();
      return $this->apiResponse($categories);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'name' => 'required|unique:data_categories,name',
      ]);
      $validator['slug'] = parent::generateSlug($validator['name']);

      $category = DataCategory::create($validator);
      return $this->apiResponse($category, 'Katagori data berhasil dibuat');
   }

   public function update(Request $request, int $id) {
      $category = DataCategory::find($id);

      $validator = $request->validate([
         'name' => [ 'required', Rule::unique('data_categories', 'name')->ignore($id) ],
      ]);
      $validator['slug'] = parent::generateSlug($validator['name']);

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
