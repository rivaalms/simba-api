<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormCommentRequest extends FormRequest
{
   /**
    * Determine if the user is authorized to make this request.
    */
   public function authorize(): bool
   {
      return true;
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
