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
        Schema::create('ims_custom_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ims_assessment_id')->constrained('ims_assessments')->cascadeOnDelete();
            $table->foreignId('service_type_id')->constrained('service_types')->cascadeOnDelete();
            $table->foreignId('guidance_field_id')->constrained('guidance_fields')->cascadeOnDelete();
            $table->text('statement_text');
            $table->text('student_note')->nullable();
            $table->boolean('is_selected')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ims_custom_statements');
    }
};
