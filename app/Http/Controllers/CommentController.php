<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormCommentRequest;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class CommentController extends Controller
{
   public function get(Request $request, int $data_id)
   {
      $comments = Comment::with(['replies' => function (HasMany $query) use ($request) {
         $query->without('replies')->sortScope($request->sort);
      }])->where('data_id', $data_id)->whereNull('reply_to')->sortScope($request->sort)->get();

      return $this->apiResponse($comments);
   }

   public function create(FormCommentRequest $request)
   {
      $comment = Comment::create($request->validated());
      $comment->data->updated_at = $comment->created_at;
      $comment->data->save();
      return $this->apiResponse($comment, 'Komentar berhasil dibuat');
   }

   public function update(FormCommentRequest $request, int $id)
   {
      $user = $request->user();
      $comment = Comment::find($id);

      if ($user->userable_type != null && $comment->user_id != $user->id) {
         return $this->apiResponse(null, 'Aksi dilarang', 403);
      }

      $comment->update($request->validated());
      $comment->data->updated_at = $comment->updated_at;
      $comment->data->save();

      return $this->apiResponse(true, 'Komentar berhasil diperbarui');
   }

   public function delete(int $id)
   {
      $user = request()->user();
      $comment = Comment::find($id);

      if ($user->userable_type != null && $comment->user_id != $user->id) {
         return $this->apiResponse(null, 'Aksi dilarang', 403);
      }

      if (!!count($comment->replies)) {
         foreach ($comment->replies as $r) {
            Comment::find($r->id)->delete();
         }
      }

      $comment->data->updated_at = \Carbon\Carbon::now();
      $comment->data->save();

      $comment->delete();
      return $this->apiResponse(true, 'Komentar berhasil dihapus');
   }
}
