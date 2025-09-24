<?php

use App\Models\Company;
use App\Models\FormTemplate;
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
        Schema::create('company_forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignIdFor(Company::class)->constrained();
            $table->foreignIdFor(FormTemplate::class)->constrained();
            $table->string('slug');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_form_templates');
    }
};
