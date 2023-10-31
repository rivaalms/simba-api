<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
   use HasFactory;

   protected $guarded = ['id'];

   protected $with = [
      'school:id,supervisor_id',
      'school.users:id,name,email,userable_type,userable_id',
      'status:id,name',
      'type:id,name,data_category_id',
      'type.category:id,name'
   ];

   public function school() {
      return $this->belongsTo(School::class);
   }

   public function type() {
      return $this->belongsTo(DataType::class, 'data_type_id', 'id');
   }

   public function status() {
      return $this->belongsTo(DataStatus::class, 'data_status_id', 'id');
   }

   public function redrafts() {
      return $this->hasMany(Redraft::class);
   }
}
