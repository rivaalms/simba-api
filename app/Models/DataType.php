<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataType extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   protected $with = ['category:id,name,slug'];

   public function data() {
      return $this->hasMany(Data::class);
   }

   public function category() {
      return $this->belongsTo(DataCategory::class, 'data_category_id', 'id');
   }

   public function scopeFilter(Builder $query, Array $filters) {
      $query->when($filters['search'] ?? false, fn (Builder $query, $search) => $query->where('name', 'like', "%$search%")->orWhere('slug', 'like', "%$search%"));

      $query->when($filters['category'] ?? false, fn (Builder $query, $category) => $query->where('data_category_id', $category));
   }
}
