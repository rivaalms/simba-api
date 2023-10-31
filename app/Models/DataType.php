<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataType extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   public function data() {
      return $this->hasMany(Data::class);
   }

   public function category() {
      return $this->belongsTo(DataCategory::class, 'data_category_id', 'id');
   }
}
