<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
   use HasApiTokens, HasFactory, Notifiable;

   /**
    * The attributes that are protected from mass assign.
    *
    * @var array<int, string>
    */
   protected $guarded = ['id'];

   /**
    * The attributes that should be hidden for serialization.
    *
    * @var array<int, string>
    */
   protected $hidden = [
      'password',
      'remember_token',
   ];

   /**
    * The attributes that should be cast.
    *
    * @var array<string, string>
    */
   protected $casts = [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
   ];

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
      $query->when($filters['search'] ?? false, fn (Builder $query, $search) => $query->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%"));
   }
}
