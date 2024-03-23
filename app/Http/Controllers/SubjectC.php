<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectReq;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectC extends Controller
{
   public function get(Request $request) {
      $subjects = Subject::filter(request(['search']))
         ->latest()
         ->paginate($request->per_page)
         ->withQueryString();
      return parent::apiResponse($subjects);
   }

   public function create(SubjectReq $request) {
      $_subject = $request->validated();
      $subject = Subject::create($_subject);
      return parent::apiResponse($subject, 'Mata pelajaran berhasil ditambahkan');
   }

   public function update(SubjectReq $request, int $id) {
      $_subject = $request->validated();
      Subject::find($id)->update($_subject);
      return parent::apiResponse(true, 'Mata pelajaran berhasil diubah');
   }

   public function delete(int $id) {
      Subject::find($id)->delete();
      return parent::apiResponse(true, 'Mata pelajaran berhasil dihapus');
   }
}
