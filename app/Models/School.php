<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
   use HasFactory;

   protected $guarded = ['id'];
   protected $with = [
      'user:id,name,email,userable_type,userable_id',
      'type:id,name',
      'supervisor:id,employee_number',
      'supervisor.user:id,name,email,userable_type,userable_id'
   ];

   public const MORPH_ALIAS = 'school';

   public function user() {
      return $this->MorphOne(User::class, 'userable');
   }

   public function supervisor() {
      return $this->belongsTo(Supervisor::class);
   }

   public function type() {
      return $this->belongsTo(SchoolType::class, 'school_type_id');
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

   public function scopeFilter(Builder $query, Array $filters) {
      $query->when($filters['type'] ?? false, fn (Builder $query, $type) => $query->where('school_type_id', $type));

      $query->when($filters['supervisor'] ?? false, fn (Builder $query, $supervisor) => $query->where('supervisor_id', $supervisor));
   }
}
