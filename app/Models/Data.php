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
      'school.user:id,name,email,userable_type,userable_id,profile_picture,status',
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

   public function scopeFilterSchool(Builder $query, $school) {
      $query->when($school ?? false, fn (Builder $query, $school)
         => $query->where('school_id', $school)
      );
   }

   public function scopeCategory(Builder $query, $category) {
      $query->when($category ?? false, fn (Builder $query, $category)
         => $query->whereHas('type', fn (Builder $query)
            => $query->where('data_category_id', $category)
         )
      );
   }

   public function scopeType(Builder $query, $type) {
      $query->when($type ?? false, fn (Builder $query, $type)
         => $query->where('data_type_id', $type)
      );
   }

   public function scopeStatus(Builder $query, $status) {
      $query->when($status ?? false, fn (Builder $query, $status)
         => $query->where('data_status_id', $status)
      );
   }

   public function scopeYear(Builder $query, $year) {
      $query->when($year ?? false, function (Builder $query) use ($year) {
         if (request()->start_year && request()->end_year) return;
         $query->where('year', $year);
      });
   }

   public function scopeSchoolSupervisor(Builder $query, $supervisor) {
      $query->when($supervisor ?? false, fn (Builder $query, $supervisor) =>
         $query->whereHas('school', fn (Builder $query) =>
            $query->where('supervisor_id', $supervisor)
         )
      );
   }

   public function scopeYearRange(Builder $query, Array $years) {
      if (request()->year) return;
      if (!$years || count($years) < 2) return;

      $_start_year = $years['start_year'];
      $_end_year = $years['end_year'];

      if ($_start_year && $_end_year) {
         $startYear = implode('-', [$_start_year, $_start_year + 1]);
         $endYear = implode('-', [$_end_year, $_end_year + 1]);

         $query->whereBetween('year', [$startYear, $endYear]);
      }
   }
}
