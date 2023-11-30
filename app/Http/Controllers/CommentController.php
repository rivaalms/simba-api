<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class CommentController extends Controller
{
   public function get(Request $request, int $data_id) {
      $comments = Comment::with(['replies' => function (HasMany $query) {
         $query->without('replies');
      }])->where('data_id', $data_id)->whereNull('reply_to')->latest()->get();
      return $this->apiResponse($comments);
   }

   public function create(Request $request) {
      $validator = $request->validate([
         'user_id' => 'required|numeric',
         'data_id' => 'required|numeric',
         'message' => 'required',
         'reply_to' => 'nullable|exists:App\Models\Comment,id'
      ]);

      $comment = Comment::create($validator);
      return $this->apiResponse($comment, 'Komentar berhasil dikirim');
   }

   public function update(Request $request, int $id) {
      $comment = Comment::find($id);

      $validator = $request->validate([
         'user_id' => 'required|numeric',
         'data_id' => 'required|numeric',
         'message' => 'required',
         'reply_to' => 'nullable|exists:App\Models\Comment,id'
      ]);

      $comment->update($validator);
      return $this->apiResponse(true, 'Komentar berhasil diperbarui');
   }

   public function delete(Request $request, int $id) {
      $comment = Comment::find($id);
      if (!!count($comment->replies)) {
         foreach ($comment->replies as $r) {
            Comment::find($r->id)->delete();
         }
      }

      $comment->delete();
      return $this->apiResponse(true, 'Komentar berhasil dihapus');
   }
}
