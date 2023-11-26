<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
   public function get(Request $request) {
      $query = Subject::filter(request(['search']))->latest();
      if ($request->per_page > 0) $subjects = $query->paginate($request->per_page)->withQueryString();
      else $subjects = $query->get();
      return $this->apiResponse($subjects);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'name' => 'required|unique:subjects,name',
         'abbreviation' => 'required|unique:subjects,abbreviation'
      ]);

      $subject = Subject::create($validator);
      return $this->apiResponse($subject, 'Mata pelajaran baru berhasil dibuat');
   }

   public function update(Request $request, int $id) {
      $subject = Subject::find($id);

      $validator = $request->validate([
         'name' => ['required', Rule::unique('subjects', 'name')->ignore($subject->id)],
         'abbreviation' => ['required', Rule::unique('subjects', 'abbreviation')->ignore($subject->id)]
      ]);

      $subject->update($validator);
      return $this->apiResponse(true, 'Mata pelajaran berhasil diperbarui');
   }

   public function delete(Request $request) {
      $validator = $request->validate([
         'id' => 'required'
      ]);
      Subject::find($validator['id'])->delete();
      return $this->apiResponse(true, 'Mata pelajaran berhasil dihapus');
   }
}
