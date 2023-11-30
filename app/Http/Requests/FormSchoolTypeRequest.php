<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormSchoolTypeRequest extends FormRequest
{
   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      if ($this->user()->userable_type) return false;
      return true;
   }

   /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */
   public function rules(): array
   {
      if ($this->method() === 'POST') $rules = [
         'name' => 'required|unique:school_types,name'
      ];
      else $rules = [
         'name' => ['required', Rule::unique('school_types', 'name')->ignore($this->route('id'))]
      ];

      return $rules;
   }
}
