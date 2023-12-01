<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormDataCategoryRequest;
use App\Models\DataCategory;
use Illuminate\Http\Request;

class DataCategoryController extends Controller
{
   public function get(Request $request)
   {
      $query = DataCategory::filter(request(['search']))->latest();
      if ($request->per_page > 0) $categories = $query->paginate($request->per_page)->withQueryString();
      else $categories = $query->get();
      return $this->apiResponse($categories);
   }

   public function create(FormDataCategoryRequest $request)
   {
      $_category = $request->validated();
      $category = DataCategory::create($_category);
      return $this->apiResponse($category, 'Kategory data berhasil dibuat');
   }

   public function update(FormDataCategoryRequest $request, int $id)
   {
      $_category = $request->validated();
      DataCategory::find($id)->update($_category);
      return $this->apiResponse(true, 'Kategori data berhasil diperbarui');
   }

   public function delete(int $id)
   {
      DataCategory::find($id)->delete();
      return $this->apiResponse(true, 'Kategori data berhasil dihapus');
   }

   public function getOptions()
   {
      $categories = DataCategory::select('name as label', 'id as value')->get()->toArray();
      return $this->apiResponse($categories);
   }
}
