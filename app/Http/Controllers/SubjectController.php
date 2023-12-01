<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormSubjectRequest;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
   public function get(Request $request)
   {
      $query = Subject::filter(request(['search']))->latest();
      if ($request->per_page > 0) $subjects = $query->paginate($request->per_page)->withQueryString();
      else $subjects = $query->get();
      return $this->apiResponse($subjects);
   }

   public function create(FormSubjectRequest $request)
   {
      $_subject = $request->validated();
      $subject = Subject::create($_subject);
      return $this->apiResponse($subject, 'Mata pelajaran baru berhasil dibuat');
   }

   public function update(FormSubjectRequest $request, int $id)
   {
      $_subject = $request->validated();
      Subject::find($id)->update($_subject);
      return $this->apiResponse(true, 'Mata pelajaran berhasil diperbarui');
   }

   public function delete(int $id)
   {
      Subject::find($id)->delete();
      return $this->apiResponse(true, 'Mata pelajaran berhasil dihapus');
   }
}
