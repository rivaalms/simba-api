<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataStatus extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   public function data() {
      return $this->hasMany(Data::class);
   }

   public function scopeFilter(Builder $query, Array $filters) {
      $query->when($filters['search'] ?? false, fn ($query, $search) => $query->where('name', 'like', "%$search%"));
   }
}
