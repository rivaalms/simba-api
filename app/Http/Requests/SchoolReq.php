<?php

namespace App\Http\Requests;

use App\Models\School;
use App\Traits\CheckUserable;
use Illuminate\Foundation\Http\FormRequest;

class SchoolReq extends FormRequest
{
   use CheckUserable;

   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      if ($this->method() === 'POST' && $this->isNotAdmin($this->user())) return false;
      if ($this->isNotAdmin($this->user()) && $this->isNotSchool($this->user())) return false;
      return true;
   }

   public function prepareForValidation() {
      $this->merge([
         'status' => 'ACTIVE'
      ]);
   }

   /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */
   public function rules(): array
   {
      if ($this->method() === 'POST') {
         $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password_confirmation' => 'required',
            'password' => 'required|min:8|string|confirmed',
            'school_type_id' => 'required|numeric',
            'supervisor_id' => 'required|numeric',
            'principal' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'required|string'
         ];
      } else {
         $school = School::find($this->route('id'));
         $rules = [
            'name'            => 'required|string',
            'email'           => "required|email|unique:users,email,{$school->user->id}",
            'school_type_id'  => 'required|numeric',
            'supervisor_id'   => 'required|numeric',
            'principal'       => 'nullable|string',
            'address'         => 'nullable|string',
            'status'          => 'required|string',
         ];
      }
      return $rules;
   }
}
