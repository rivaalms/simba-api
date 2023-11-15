<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SchoolStudent extends Model
{
   use HasFactory;

   protected $guarded = ['id'];
   protected $with = [
      'religion:id,name'
   ];

   public function school() {
      return $this->belongsTo(School::class);
   }

   public function religion() {
      return $this->belongsTo(Religion::class);
   }

   public function scopeFilter(Builder $query, Array $filters) {
      $query->when($filters['year'] ?? false, fn (Builder $query, $year) => $query->where('year', 'like', "%$year%"));
   }
}
