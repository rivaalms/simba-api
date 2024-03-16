<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\CheckUserable;

class DataReq extends FormRequest
{
   use CheckUserable;

   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      if ($this->isSchool($this->user()) || !$this->user()->userable_type) return true;
      return false;
   }

   protected function prepareForValidation()
   {
      if ($this->isSchool($this->user())) {
         $this->merge([
            'school_id' => $this->user()->userable_id
         ]);
      }
   }

   /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */
   public function rules(): array
   {
      $route = $this->route()->getName();
      if ($route === 'data:create') {
         $rules = [
            'school_id' => 'required|exists:App\Models\School,id',
            'year' => 'required|string',
            'data_type_id' => 'required|exists:App\Models\DataType,id',
            'data_status_id' => 'required|exists:App\Models\DataStatus,id',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.google-apps.document,application/vnd.google-apps.spreadsheet'
         ];
      } else if ($route === 'data:update') {
         $rules = [
            'school_id' => 'required|exists:App\Models\School,id',
            'year' => 'required|string',
            'data_type_id' => 'required|exists:App\Models\DataType,id',
            'data_status_id' => 'required|exists:App\Models\DataStatus,id',
         ];
      } else {
         $rules = [
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.google-apps.document,application/vnd.google-apps.spreadsheet'
         ];
      }

      return $rules;
   }
}
