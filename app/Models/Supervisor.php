<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supervisor extends Model
{
   use HasFactory;

   public const MORPH_ALIAS = 'supervisor';

   protected $guarded = ['id'];
   protected $with = [
      'user:id,name,email,userable_type,userable_id,status',
   ];

   public function user() {
      return $this->morphOne(User::class, 'userable');
   }

   public function schools() {
      return $this->hasMany(School::class);
   }

   public function scopeFilter($query, Array $filters) {
      $query->when($filters['search'] ?? false, fn ($query, $search) => $query->whereHas('user', function (Builder $query) use ($search) {
         return $query->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%");
      })->orWhere('employee_number', 'like', "%$search%"));
   }
}
