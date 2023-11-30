<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   protected $with = [
      'school:id,supervisor_id',
      'school.user:id,name,email,userable_type,userable_id',
      'status:id,name',
      'type:id,name,data_category_id',
      'type.category:id,name'
   ];

   public function school() {
      return $this->belongsTo(School::class);
   }

   public function type() {
      return $this->belongsTo(DataType::class, 'data_type_id', 'id');
   }

   public function status() {
      return $this->belongsTo(DataStatus::class, 'data_status_id', 'id');
   }

   public function comments() {
      return $this->hasMany(Comment::class);
   }

   public function scopeFilter(Builder $query, Array $filters) {
      $query->when($filters['school'] ?? false, fn (Builder $query, $school) => $query->where('school_id', $school));

      $query->when($filters['category'] ?? false, fn (Builder $query, $category) =>
         $query->whereHas('type', fn ($query) =>
            $query->where('data_category_id', $category)
         )
      );

      $query->when($filters['type'] ?? false, fn (Builder $query, $type) => $query->where('data_type_id', $type));

      $query->when($filters['status'] ?? false, fn (Builder $query, $status) => $query->where('data_status_id', $status));

      $query->when($filters['year'] ?? false, fn (Builder $query, $year) => $query->where('year', 'like', "%{$year}%"));
   }
}
