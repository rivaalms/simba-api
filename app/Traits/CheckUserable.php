<?php

namespace App\Traits;

use App\Models\User;

trait CheckUserable
{
   public function isAdmin(User $user) {
      return !$user->userable_type;
   }

   public function isSchool(User $user) {
      return $user->userable_type == 'school';
   }

   public function isSupervisor(User $user) {
      return $user->userable_type == 'supervisor';
   }

   public function isOfficer(User $user) {
      return $user->userable_type == 'officer';
   }

   public function isNotAdmin(User $user) {
      return !!$user->userable_type;
   }

   public function isNotSchool(User $user) {
      return $user->userable_type != 'school';
   }

   public function isNotSupervisor(User $user) {
      return $user->userable_type != 'supervisor';
   }

   public function isNotOfficer(User $user) {
      return $user->userable_type != 'officer';
   }
}
