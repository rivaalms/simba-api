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
      $user = $this->user();
      if ($user->userable_type == null) return true;

      if ($user->userable_type == 'school') {
         if ($this->school_id && $this->school_id != $user->userable_id) return false;
         return true;
      }

      return false;
   }

   public function prepareForValidation(): void
   {
      $user = $this->user();
      if ($user->userable_type == 'school') {
         $this->merge(['school_id' => $user->userable_id]);
      }
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
