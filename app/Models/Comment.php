<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
   use HasFactory;

   protected $guarded = ['id'];
   protected $with = [
      'user:id,name,email,userable_type',
      'replies'
   ];

   public function data() {
      return $this->belongsTo(Data::class);
   }

   public function user() {
      return $this->belongsTo(User::class);
   }

   public function reply_to() {
      return $this->belongsTo(Comment::class, 'reply_to', 'id');
   }

   public function replies() {
      return $this->hasMany(Comment::class, 'reply_to', 'id');
   }
}
