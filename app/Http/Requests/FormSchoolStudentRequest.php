<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormSchoolStudentRequest extends FormRequest
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
         'school_id' => 'required|exists:schools,id',
         'year' => 'required',
         'grade' => 'required|numeric',
         'religion_id' => 'required|exists:religions,id',
         'count' => 'required|numeric'
      ];
   }
}
