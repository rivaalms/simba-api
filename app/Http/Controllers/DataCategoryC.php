<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataCategoryReq;
use App\Models\DataCategory;
use Illuminate\Http\Request;

class DataCategoryC extends Controller
{
   public function get(Request $request) {
      $categories = DataCategory::filter(request(['search']))
         ->latest()
         ->paginate($request->per_page)
         ->withQueryString();
      return parent::apiResponse($categories);
   }

   public function create(DataCategoryReq $request) {
      $_category = $request->validated();
      $category = DataCategory::create($_category);
      return parent::apiResponse($category, 'Kategori data berhasil dibuat');
   }

   public function update(DataCategoryReq $request, int $id) {
      $_category = $request->validated();
      DataCategory::find($id)->update($_category);
      return parent::apiResponse(true, 'Kategori data berhasil diperbarui');
   }

   public function delete(int $id) {
      DataCategory::find($id)->delete();
      return parent::apiResponse(true, 'Kategori data berhasil dihapus');
   }
}
