<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormSchoolTeacherRequest extends FormRequest
{
   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      $userable = $this->user()->userable_type;
      if ($userable === 'school' || $userable === null) return true;
      return false;
   }

   /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */
   public function rules(): array
   {
      return [
         'school_id' => 'required|numeric|exists:schools,id',
         'year' => 'required',
         'subject_id' => 'required|numeric|exists:subjects,id',
         'count' => 'required|numeric'
      ];
   }
}
