<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolTeacher extends Model
{
   use HasFactory;

   protected $guarded = ['id'];
   protected $with = [
      'subject:id,name,abbreviation'
   ];

   public function school() {
      return $this->belongsTo(School::class);
   }

   public function subject() {
      return $this->belongsTo(Subject::class);
   }

   public function scopeFilter(Builder $query, Array $filters) {
      $query->when($filters['year'] ?? false, fn (Builder $query, $year) =>
         $query->where('year', 'like', "%$year%")
      );
   }
}
