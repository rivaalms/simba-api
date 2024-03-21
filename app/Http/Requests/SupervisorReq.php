<?php

namespace App\Http\Requests;

use App\Models\Supervisor;
use App\Traits\CheckUserable;
use Illuminate\Foundation\Http\FormRequest;

class SupervisorReq extends FormRequest
{
   use CheckUserable;

   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      if ($this->method() == 'POST' && $this->isNotAdmin($this->user())) {
         return false;
      }

      if ($this->isNotAdmin($this->user()) && $this->isNotSupervisor($this->user())) {
         return false;
      }

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
