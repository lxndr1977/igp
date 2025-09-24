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
      Schema::create('form_responses', function (Blueprint $table) {
         $table->id();
         $table->morphs('subject');
         $table->string('respondent_email')->nullable();
         $table->string('respondent_name')->nullable();
         $table->ipAddress('ip_address')->nullable();
         $table->string('user_agent')->nullable();
         $table->timestamp('submitted_at');
         $table->timestamps();

         $table->index('respondent_email');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('company_form_responses');
   }
};
