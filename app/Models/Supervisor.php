<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   public function userable() {
      return $this->morphMany(User::class, 'userable');
   }

   public function schools() {
      return $this->hasMany(School::class);
   }
}
