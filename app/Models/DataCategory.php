<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCategory extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   public function types() {
      return $this->hasMany(DataType::class);
   }
}
