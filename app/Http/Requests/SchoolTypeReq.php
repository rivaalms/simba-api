<?php

namespace App\Http\Requests;

use App\Traits\CheckUserable;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SchoolTypeReq extends FormRequest
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
         'name' => 'required|unique:school_types,name'
      ];
      else $rules = [
         'name' => ['required', Rule::unique('school_types', 'name')->ignore($this->route('id'))]
      ];

      return $rules;
   }
}
