<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   public function users() {
      return $this->morphMany(User::class, 'userable');
   }

   public function supervisor() {
      return $this->belongsTo(Supervisor::class);
   }

   public function type() {
      return $this->belongsTo(SchoolType::class);
   }

   public function data() {
      return $this->hasMany(Data::class);
   }

   public function students() {
      return $this->hasMany(SchoolStudent::class);
   }

   public function teachers() {
      return $this->hasMany(SchoolTeacher::class);
   }
}
