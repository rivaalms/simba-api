<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      Schema::create('school_teachers', function (Blueprint $table) {
         $table->id();
         $table->foreignId('school_id');
         $table->string('year');
         $table->foreignId('subject_id');
         $table->integer('count');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('school_teachers');
   }
};
