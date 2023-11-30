<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
   public function get(Request $request, int $data_id) {
      $comments = Comment::where('data_id', $data_id)->whereNull('reply_to')->latest()->get();
      return $this->apiResponse($comments);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'user_id' => 'required|numeric',
         'data_id' => 'required|numeric',
         'message' => 'required',
         'reply_to' => 'required|numeric|exist:App\Models\Comment,id'
      ]);

      $comment = Comment::create($validator);
      return $this->apiResponse($comment, 'Pesan berhasil dikirim');
   }

   public function update(Request $request, int $id) {
      $comment = Comment::find($id);

      $validator = $request->validate([
         'user_id' => 'required|numeric',
         'data_id' => 'required|numeric',
         'message' => 'required'
      ]);

      $comment->update($validator);
      return $this->apiResponse(true, 'Pesan berhasil diperbarui');
   }

   public function delete(Request $request, int $id) {
      Comment::find($id)->delete();
      return $this->apiResponse(true, 'Pesan berhasil dihapus');
   }
}
