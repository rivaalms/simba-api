<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class School extends Model
{
   use HasFactory;

   public const MORPH_ALIAS = 'school';

   protected $guarded = ['id'];
   protected $with = [
      'user:id,name,email,userable_type,userable_id,status,profile_picture',
      'type:id,name',
      'supervisor:id,employee_number',
      'supervisor.user:id,name,email,userable_type,userable_id'
   ];

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

   public function scopeType(Builder $query, int|null $type) {
      $query->when($type ?? false, fn (Builder $query, $type) =>
         $query->where('school_type_id', $type)
      );
   }

   public function scopeSearch(Builder $query, string|null $search) {
      $query->when($search ?? false, fn (Builder $query, $search) =>
         $query->whereHas('user', fn (Builder $query) =>
            $query->where('name', 'like', "%{$search}%")
               ->orWhere('email', 'like', "%{$search}%")
         )
            ->orWhere('principal', 'like', "%{$search}%")
      );
   }

   public function scopeSupervisor(Builder $query, int|null $supervisor) {
      $query->when($supervisor ?? false, fn (Builder $query, $supervisor) =>
         $query->where('supervisor_id', $supervisor)
      );
   }
}
