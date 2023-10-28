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
      Schema::create('school_students', function (Blueprint $table) {
         $table->id();
         $table->foreignId('school_id');
         $table->integer('grade');
         $table->string('year');
         $table->integer('islam');
         $table->integer('catholic');
         $table->integer('protestant');
         $table->integer('hindu');
         $table->integer('buddha');
         $table->integer('konghucu');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('school_students');
   }
};
