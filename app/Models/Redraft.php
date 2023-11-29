<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redraft extends Model
{
   use HasFactory;

   protected $guarded = ['id'];
   protected $with = [
      'user:id,name,email,userable_type'
   ];

   public function data() {
      return $this->belongsTo(Data::class);
   }

   public function user() {
      return $this->belongsTo(User::class);
   }

   // public static function mapRedrafts($data) {
   //    $hasChildren = $data->whereNotNull('parent');
   //    dd($hasChildren);
   // }
}
