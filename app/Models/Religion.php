<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   public function school_students() {
      return $this->hasMany(SchoolStudent::class);
   }

   public function scopeFilter(Builder $query, Array $filters) {
      $query->when($filters['search'] ?? false, fn (Builder $query, $search) => $query->where('name', 'like', "%$search%"));
   }
}
