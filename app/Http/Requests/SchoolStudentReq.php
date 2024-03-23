<?php

namespace App\Http\Requests;

use App\Traits\CheckUserable;
use Illuminate\Foundation\Http\FormRequest;

class SchoolStudentReq extends FormRequest
{
   use CheckUserable;
   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      return $this->isAdmin($this->user())
         || ($this->isSchool($this->user()) && (!$this->school_id || $this->school_id == $this->user()->userable_id));
   }

   public function prepareForValidation() {
      if ($this->isSchool($this->user())) {
         $this->merge(['school_id' => $this->user()->userable_id]);
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
