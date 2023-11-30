<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FormDataCategoryRequest extends FormRequest
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
    * Prepare the data for validation.
    */
   protected function prepareForValidation(): void
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
      if ($this->method() === "POST") {
         $rules = [
            'name' => 'required|unique:data_categories,name',
            'slug' => 'required|unique:data_categories,slug'
         ];
      } else {
         $rules = [
            'name' => ['required', Rule::unique('data_categories', 'name')->ignore($this->route('id'))],
            'slug' => [ 'required', Rule::unique('data_categories', 'slug')->ignore($this->route('id')) ]
         ];
      }

      return $rules;
   }
}
