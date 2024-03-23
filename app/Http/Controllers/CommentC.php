<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentReq;
use App\Models\Comment;
use App\Traits\CheckUserable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class CommentC extends Controller
{
   use CheckUserable;

   public function get(Request $request, int $data_id) {
      $comments = Comment::with(['replies' => fn (HasMany $query) =>
         $query->without('replies')->sortScope($request->sort)
      ])
         ->where('data_id', $data_id)
         ->whereNull('reply_to')
         ->sortScope($request->sort)
         ->get();

      return parent::apiResponse($comments);
   }

   public function create(CommentReq $request) {
      $comment = Comment::create($request->validated());
      $comment->data->updated_at = $comment->created_at;
      $comment->data->save();
      return parent::apiResponse($comment, 'Komentar berhasil dibuat');
   }

   public function update(CommentReq $request, int $id) {
      $user = $request->user();
      $comment = Comment::find($id);

      if (!$comment) {
         return parent::apiResponse(null, 'Komentar tidak ditemukan', 404);
      }

      if ($this->isNotAdmin($user) && $comment->user_id != $user->id) {
         return parent::apiResponse(null, 'Aksi tidak diizinkan', 403);
      }

      $comment->update($request->validated());
      $comment->data->updated_at = $comment->updated_at;
      $comment->data->save();

      return parent::apiResponse(true, 'Komentar berhasil diperbarui');
   }

   public function delete(int $id) {
      $user = request()->user();
      $comment = Comment::find($id);

      if ($this->isNotAdmin($user) && $comment->user_id != $user->id) {
         return parent::apiResponse(null, 'Aksi tidak diizinkan', 403);
      }

      if (!!count($comment->replies)) {
         foreach ($comment->replies as $r) {
            Comment::find($r->id)->delete();
         }
      }

      $comment->data->updated_at = \Carbon\Carbon::now();
      $comment->data->save();

      $comment->delete();
      return parent::apiResponse(true, 'Komentar berhasil dihapus');
   }
}
