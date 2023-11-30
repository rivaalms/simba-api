<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormCommentRequest;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class CommentController extends Controller
{
   public function get(Request $request, int $data_id) {
      $comments = Comment::with(['replies' => function (HasMany $query) use ($request) {
         $query->without('replies')->sortScope($request->sort);
      }])->where('data_id', $data_id)->whereNull('reply_to')->sortScope($request->sort)->get();

      return $this->apiResponse($comments);
   }

   public function create(FormCommentRequest $request) {
      $comment = Comment::create($request->validated());
      return $this->apiResponse($comment, 'Komentar berhasil dibuat');
   }

   public function update(FormCommentRequest $request, int $id) {
      Comment::find($id)->update($request->validated());
      return $this->apiResponse(true, 'Komentar berhasil diperbarui');
   }

   public function delete(int $id) {
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
