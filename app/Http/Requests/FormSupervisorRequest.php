<?php

namespace App\Http\Requests;

use App\Models\Supervisor;
use Illuminate\Foundation\Http\FormRequest;

class FormSupervisorRequest extends FormRequest
{
   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      $userable = $this->user()->userable_type;
      if ($this->method() === 'POST' && $userable) return false;
      if ($userable && $userable !== 'supervisor') return false;
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
            'employee_number' => 'required|string|unique:supervisors,employee_number',
            'password' => 'required|min:8|string'
         ];
      } else {
         $supervisor = Supervisor::find($this->route('id'));
         $rules = [
            'name' => 'required|string',
            'email' => "required|email|unique:users,email,{$supervisor->user->id}",
            'employee_number' => "required|string|unique:supervisors,employee_number,{$supervisor->id}",
         ];
      }

      return $rules;
   }
}
