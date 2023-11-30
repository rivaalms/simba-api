<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormSubjectRequest extends FormRequest
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
         'name' => 'required|unique:subjects,name',
         'abbreviation' => 'required|unique:subjects,abbreviation'
      ];
      else $rules = [
         'name' => ['required', Rule::unique('subjects', 'name')->ignore($this->route('id'))],
         'abbreviation' => ['required', Rule::unique('subjects', 'abbreviation')->ignore($this->route('id'))],
      ];

      return $rules;
   }
}
