<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormSchoolTypeRequest;
use App\Models\SchoolType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolTypeController extends Controller
{
   public function get(Request $request)
   {
      $query = SchoolType::filter(request(['search']))->latest();
      if ($request->per_page > 0) $types = $query->paginate($request->per_page)->withQueryString();
      else $types = $query->get();
      return $this->apiResponse($types);
   }

   public function create(FormSchoolTypeRequest $request)
   {
      $_type = $request->validated();
      $type = SchoolType::create($_type);
      return $this->apiResponse($type, 'Tipe sekolah baru berhasil dibuat');
   }

   public function update(FormSchoolTypeRequest $request, int $id)
   {
      $_type = $request->validated();
      SchoolType::find($id)->update($_type);
      return $this->apiResponse(true, 'Tipe sekolah berhasil diperbarui');
   }

   public function delete(int $id)
   {
      SchoolType::find($id)->delete();
      return $this->apiResponse(true, 'Tipe sekolah berhasil dihapus');
   }

   public function getOptions()
   {
      $data = SchoolType::select('name as label', 'id as value')->distinct('id')->get()->toArray();
      return $this->apiResponse($data);
   }
}
