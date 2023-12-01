<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormDataTypeRequest;
use App\Models\DataType;
use Illuminate\Http\Request;

class DataTypeController extends Controller
{
   public function get(Request $request)
   {
      $query = DataType::filter(request(['search', 'category']))->latest();
      if ($request->per_page > 0) $types = $query->paginate($request->per_page)->withQueryString();
      else $types = $query->get();
      return $this->apiResponse($types);
   }

   public function create(FormDataTypeRequest $request)
   {
      $_type = $request->validated();
      $type = DataType::create($_type);
      return $this->apiResponse($type, 'Tipe data baru berhasil dibuat');
   }

   public function update(FormDataTypeRequest $request, int $id)
   {
      $_type = $request->validated();
      DataType::find($id)->update($_type);
      return $this->apiResponse(true, 'Tipe data berhasil diperbarui');
   }

   public function delete(int $id)
   {
      DataType::find($id)->delete();
      return $this->apiResponse(true, 'Tipe data berhasil dihapus');
   }

   public function getOptions(Request $request)
   {
      $types = DataType::select('name as label', 'id as value')->where('data_category_id', $request->category)->get();
      return $this->apiResponse($types);
   }
}
