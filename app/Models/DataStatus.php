<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataStatus extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   public function data() {
      return $this->hasMany(Data::class);
   }
}
