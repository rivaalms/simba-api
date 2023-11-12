<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
   use HasFactory;

   public const MORPH_ALIAS = 'officer';

   protected $guarded = ['id'];
   protected $with = [
      'user:id,name,email,userable_type,userable_id'
   ];

   public function user() {
      return $this->morphOne(User::class, 'userable');
   }

   public function scopeFilter(Builder $query, Array $filters) {
      $query->when($filters['search'] ?? false, function (Builder $query, $search) {
         return $query->whereHas('user', function (Builder $query) use ($search) {
            return $query->where('name', 'like', "%$search%")
               ->orWhere('email', 'like', "%$search%");
         })->orWhere('employee_number', 'like', "%$search%");
      });
   }
}
