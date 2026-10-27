<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Master 180 Butir Pernyataan IMS
        Schema::create('ims_statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('statement_number')->unique();
            $table->foreignId('service_type_id')->constrained('service_types')->cascadeOnDelete();
            $table->foreignId('guidance_field_id')->constrained('guidance_fields')->cascadeOnDelete();
            $table->text('statement_text');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Sesi Pengisian IMS per Murid per Semester
        Schema::create('ims_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->text('final_notes')->nullable(); // Catatan akhir opsional murid
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['student_id', 'semester_id'], 'unique_student_semester_assessment');
        });

        // 3. Detail Pilihan Butir Murid & Catatan Manual per Butir
        Schema::create('ims_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ims_assessment_id')->constrained('ims_assessments')->cascadeOnDelete();
            $table->foreignId('ims_statement_id')->constrained('ims_statements')->cascadeOnDelete();
            $table->boolean('is_selected')->default(false);
            $table->text('student_note')->nullable(); // Catatan manual pada butir yang dipilih
            $table->timestamps();

            $table->unique(['ims_assessment_id', 'ims_statement_id'], 'unique_assessment_statement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ims_answers');
        Schema::dropIfExists('ims_assessments');
        Schema::dropIfExists('ims_statements');
    }
};
