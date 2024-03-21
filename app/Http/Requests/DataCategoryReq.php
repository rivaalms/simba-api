<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use App\Traits\CheckUserable;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DataCategoryReq extends FormRequest
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

   /**
    * Prepare the data for validation
    */
   protected function prepareForValidation() {
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
