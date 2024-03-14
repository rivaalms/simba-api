<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolType extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   public function schools() {
      return $this->hasMany(School::class);
   }

   public function scopeFilter(Builder $query, Array $filters) {
      $query->when($filters['search'] ?? false, fn (Builder $query, $search) =>
         $query->where('name', 'like', "%$search%")
      );
   }
}
