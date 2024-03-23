<?php

namespace App\Http\Requests;

use App\Traits\CheckUserable;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SubjectReq extends FormRequest
{
   use CheckUserable;

   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      return $this->isAdmin($this->user());
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
