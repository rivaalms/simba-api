<?php

namespace App\Http\Requests;

use App\Models\Officer;
use App\Models\School;
use App\Models\Supervisor;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\CheckUserable;
use Illuminate\Validation\Rule;

class UserReq extends FormRequest
{
   use CheckUserable;

   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      return true;
   }

   protected function prepareForValidation() {
      if ($this->isSchool($this->user())) {
         $principal = $this->principal ?? null;
         $address = $this->address ?? null;

         $this->merge(compact('principal', 'address'));
      }
   }

   /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */
   public function rules(): array
   {
      $user = $this->user();
      $rules = [
         'name' => 'required|string',
         'email' => [
            'required',
            Rule::unique('users', 'email')->ignore($user->id)
         ],
         'password' => 'required',
      ];

      switch ($user->userable_type) {
         case School::MORPH_ALIAS:
            $rules = array_merge($rules, [
               'school_type_id' => 'required|numeric|exists:App\Models\SchoolType,id',
               'supervisor_id' => 'required|numeric,exists:App\Models\Supervisor,id',
               'principal' => 'nullable|string',
               'address' => 'nullable|string'
            ]);
            break;

         case Supervisor::MORPH_ALIAS:
            $rules = array_merge($rules, [
               'employee_number' => [
                  'required',
                  'numeric',
                  Rule::unique('supervisors', 'employee_number')->ignore($user->userable->id)
               ]
            ]);
            break;

         case Officer::MORPH_ALIAS:
            $rules = array_merge($rules, [
               'employee_number' => [
                  'required',
                  'numeric',
                  Rule::unique('officers', 'employee_number')->ignore($user->userable->id)
               ]
            ]);
            break;

         default: break;
      }

      return $rules;
   }
}
