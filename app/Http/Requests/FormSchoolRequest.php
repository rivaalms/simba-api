<?php

namespace App\Http\Requests;

use App\Models\School;
use Illuminate\Foundation\Http\FormRequest;

class FormSchoolRequest extends FormRequest
{
   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      $userable = $this->user()->userable_type;
      if ($this->method() === 'POST' && $userable) return false;
      if ($userable && $userable !== 'school') return false;
      return true;
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
            'password' => 'required|min:8|string',
            'school_type_id' => 'required|numeric',
            'supervisor_id' => 'required|numeric',
            'principal' => 'nullable|string',
            'address' => 'nullable|string'
         ];
      } else {
         $school = School::find($this->route('id'));
         $rules = [
            'name'            => 'required|string',
            'email'           => "required|email|unique:users,email,{$school->user->id}",
            'school_type_id'  => 'required|numeric',
            'supervisor_id'   => 'required|numeric',
            'principal'       => 'nullable|string',
            'address'         => 'nullable|string'
         ];
      }
      return $rules;
   }
}
