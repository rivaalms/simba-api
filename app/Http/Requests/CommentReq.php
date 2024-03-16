<?php

namespace App\Http\Requests;

use App\Traits\CheckUserable;
use Illuminate\Foundation\Http\FormRequest;

class CommentReq extends FormRequest
{
   use CheckUserable;

   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      return true;
   }

   public function prepareForValidation() {
      $user = $this->user();
      if ($this->isNotAdmin($user)) {
         $this->merge([
            'user_id' => $user->id
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
      return [
         'user_id' => 'required|numeric',
         'data_id' => 'required|numeric',
         'message' => 'required',
         'reply_to' => 'nullable|exists:App\Models\Comment,id'
      ];
   }
}
