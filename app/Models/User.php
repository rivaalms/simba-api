<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
   use HasFactory, Notifiable, HasApiTokens;

   protected $guarded = ['id'];
   protected $hidden = [
      'password',
      'remember_token',
   ];
   protected function casts(): array
   {
      return [
         'email_verified_at' => 'datetime',
         'password' => 'hashed',
      ];
   }

   public const USER_FIELDS = ['name', 'email', 'password', 'userable_type', 'userable_id', 'status'];

   public const USER_STATUS = [
      'active' => 'ACTIVE',
      'inactive' => 'INACTIVE'
   ];

   public function userable() {
      return $this->morphTo();
   }

   public function comments() {
      return $this->hasMany(Comment::class);
   }

   public function scopeFilter(Builder $query, Array $filters) {
      $query->when($filters['search'] ?? false, fn (Builder $query, $search) =>
         $query->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
      );
   }
}
