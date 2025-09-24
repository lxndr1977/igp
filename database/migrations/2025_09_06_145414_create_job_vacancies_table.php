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
      Schema::create('job_vacancies', function (Blueprint $table) {
         $table->id();
         $table->foreignId('company_id')->constrained()->onDelete('cascade');
         $table->foreignId('form_template_id')->nullable()->constrained()->onDelete('set null');

         $table->string('title');
         $table->string('slug')->unique();
         $table->text('description');
         $table->text('requirements')->nullable();
         $table->text('benefits')->nullable();

         $table->enum('employment_type', [
            'clt',
            'service_provider',
            'temporary',
            'self_employed',
            'freelancer',
            'trainee',
            'cooperated',
         ])->default('full_time');

         $table->enum('work_location', [
            'on_site',
            'remote',
            'hybrid'
         ])->default('on_site');

         $table->string('department')->nullable();
         $table->string('level', 50)->nullable();

         $table->string('city')->nullable();
         $table->string('state')->nullable();
         $table->string('country')->default('BR');

         $table->decimal('salary_min', 10, 2)->nullable();
         $table->decimal('salary_max', 10, 2)->nullable();

         $table->boolean('show_salary')->default(true);
         $table->boolean('show_company_name')->default(true);

         $table->date('application_deadline')->nullable();
         $table->enum('status', [
            'draft',
            'active',
            'paused',
            'closed',
            'cancelled'
         ])->default('draft');

         $table->boolean('is_featured')->default(false);

         $table->timestamps();

         $table->index(['company_id', 'status']);
         $table->index(['status', 'application_deadline']);
         $table->index('employment_type');
         $table->index('work_location');
         $table->index(['city', 'state']);
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('job_vacancies');
   }
};
