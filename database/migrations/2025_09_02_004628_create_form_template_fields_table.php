<?php

use App\Models\FormTemplateSection;
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
      Schema::create('form_template_fields', function (Blueprint $table) {
         $table->id();
         $table->foreignIdFor(FormTemplateSection::class)->constrained()->cascadeOnDelete();
         $table->enum('field_type', [
            'text',
            'textarea',
            'email',
            'number',
            'tel',
            'select_single',
            'select_multiple',
            'radio',
            'checkbox',
            'date',
            'rating',
            'scale'
         ]);
         $table->string('label');
         $table->string('placeholder')->nullable();
         $table->text('help_text')->nullable();
         $table->boolean('is_required')->default(false);
         $table->integer('order')->default(0);
         $table->json('options')->nullable();
         $table->json('validation_rules')->nullable();
         $table->json('field_config')->nullable();
         $table->boolean('is_active')->default(true);
         $table->timestamps();

         $table->index(['form_template_section_id', 'order']);
         $table->index(['form_template_section_id', 'is_active']);
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('form_template_fields');
   }
};
