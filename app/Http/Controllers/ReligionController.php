<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormReligionRequest;
use App\Models\Religion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReligionController extends Controller
{
   public function get(Request $request)
   {
      $query = Religion::filter(request(['search']))->latest();
      if ($request->per_page > 0) $religions = $query->paginate($request->per_page)->withQueryString();
      else $religions = $query->get();
      return $this->apiResponse($religions);
   }

   public function create(FormReligionRequest $request)
   {
      $_religion = $request->validated();
      $religion = Religion::create($_religion);
      return $this->apiResponse($religion, 'Data agama baru berhasil dibuat');
   }

   public function update(FormReligionRequest $request, int $id)
   {
      $_religion = $request->validated();
      Religion::find($id)->update($_religion);
      return $this->apiResponse(true, 'Data agama berhasil diperbarui');
   }

   public function delete(int $id)
   {
      Religion::find($id)->delete();
      return $this->apiResponse(true, 'Data agama berhasil dihapus');
   }
}
