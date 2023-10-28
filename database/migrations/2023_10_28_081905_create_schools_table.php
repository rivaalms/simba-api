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
      Schema::create('schools', function (Blueprint $table) {
         $table->id();
         $table->foreignId('school_type_id');
         $table->foreignId('supervisor_id');
         $table->string('principal')->nullable();
         $table->text('address')->nullable();
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('schools');
   }
};
