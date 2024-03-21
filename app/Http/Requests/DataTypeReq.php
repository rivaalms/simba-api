<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use App\Traits\CheckUserable;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DataTypeReq extends FormRequest
{
   use CheckUserable;

   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      if ($this->isNotAdmin($this->user())) {
         return false;
      }
      return true;
   }

   protected function prepareForValidation()
   {
      $this->merge([
         'slug' => Str::slug($this->name)
      ]);
   }

   /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */
   public function rules(): array
   {
      if ($this->method() === 'POST') $rules = [
         'name' => 'required|unique:data_types,name',
         'slug' => 'required|unique:data_types,slug',
         'data_category_id' => 'required|numeric|exists:data_categories,id'
      ];
      else $rules = [
         'name' => ['required', Rule::unique('data_types', 'name')->ignore($this->route('id'))],
         'slug' => ['required', Rule::unique('data_types', 'slug')->ignore($this->route('id'))],
         'data_category_id' => 'required|numeric|exists:data_categories,id'
      ];

      return $rules;
   }
}
