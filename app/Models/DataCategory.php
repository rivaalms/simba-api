<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCategory extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   public function types() {
      return $this->hasMany(DataType::class);
   }

   public function scopeFilter(Builder $query, Array $filters) {
      $query->when($filters['search'] ?? false, fn (Builder $query, string $search) =>
         $query->where('name', 'like', "%$search%")->orWhere('slug', 'like', "%$search%")
      );
   }
}
