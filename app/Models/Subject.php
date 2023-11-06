<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
   use HasFactory;

   public function school_teachers() {
      return $this->hasMany(SchoolTeacher::class);
   }
}
