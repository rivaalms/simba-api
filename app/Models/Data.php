<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   public function school() {
      return $this->belongsTo(School::class);
   }

   public function type() {
      return $this->belongsTo(DataType::class);
   }

   public function status() {
      return $this->belongsTo(DataStatus::class);
   }

   public function redrafts() {
      return $this->hasMany(Redraft::class);
   }
}
