<?php

use App\Models\FormResponse;
use App\Models\FormTemplateField;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
   /** 
    * Run the migrations.
    */
   public function up(): void
   {
      Schema::create('form_field_responses', function (Blueprint $table) {
         $table->id();
         $table->foreignIdFor(FormResponse::class)->constrained()->cascadeOnDelete();
         $table->foreignIdFor(FormTemplateField::class)->constrained()->cascadeOnDelete();
         $table->text('value')->nullable(); 
         $table->timestamps();

         $table->index(['form_response_id']);
         $table->index(['form_template_field_id']);

         // $table->unique(['form_response_id', 'form_template_field_id']);
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('company_form_field_responses');
   }
};
