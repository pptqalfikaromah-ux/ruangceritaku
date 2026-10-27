<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pengajuan Agenda Konseling Mandiri Murid
        Schema::create('counseling_requests', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 50)->unique();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('purpose'); // Tujuan / alasan konseling
            $table->foreignId('guidance_field_id')->nullable()->constrained('guidance_fields')->nullOnDelete();
            $table->foreignId('service_type_id')->nullable()->constrained('service_types')->nullOnDelete();
            $table->boolean('is_service_unknown')->default(false); // Murid belum tahu jenis layanan
            $table->text('description'); // Deskripsi singkat kebutuhan
            $table->text('additional_notes')->nullable(); // Catatan tambahan
            $table->string('preferred_schedule')->nullable(); // Pilihan waktu/hari
            $table->enum('status', [
                'pending',
                'needs_information',
                'approved',
                'awaiting_student_confirmation',
                'scheduled',
                'completed',
                'rejected',
                'cancelled',
                'reschedule_requested'
            ])->default('pending');
            $table->foreignId('assigned_counselor_id')->nullable()->constrained('counselors')->nullOnDelete();
            $table->text('status_notes')->nullable(); // Catatan respon / alasan penolakan petugas
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Riwayat Status & Audit Trail Pengajuan Konseling
        Schema::create('counseling_request_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counseling_request_id')->constrained('counseling_requests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Petugas / Murid yang mengubah
            $table->string('status_from', 50);
            $table->string('status_to', 50);
            $table->text('notes')->nullable(); // Alasan / instruksi
            $table->timestamps();
        });

        // 3. Jadwal Konkret Konseling (Terpisah dari Status Persetujuan)
        Schema::create('counseling_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counseling_request_id')->constrained('counseling_requests')->cascadeOnDelete();
            $table->foreignId('counselor_id')->constrained('counselors')->cascadeOnDelete();
            $table->date('scheduled_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location')->default('Ruang BK');
            $table->string('schedule_status', 50)->default('proposed');
            $table->string('student_confirmation', 50)->default('pending');
            $table->text('reschedule_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Jalur Pengaduan Anak Terpisah (Kasus, Bullying, dll)
        Schema::create('student_complaints', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 50)->unique();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->boolean('is_anonymous')->default(false);
            $table->string('reporter_name')->nullable();
            $table->string('urgency_level', 50)->default('sedang');
            $table->string('category', 150)->default('Perundungan / Bullying');
            $table->date('incident_date')->nullable();
            $table->string('incident_location')->nullable();
            $table->text('description');
            $table->string('status', 50)->default('pending');
            $table->boolean('safety_risk_flag')->default(false); // Eskalasi darurat keselamatan anak
            $table->text('handling_notes')->nullable();
            $table->foreignId('handled_by_counselor_id')->nullable()->constrained('counselors')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Pencatatan Realisasi 9 Layanan BK & Tindak Lanjut
        Schema::create('counseling_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_type_id')->constrained('service_types')->cascadeOnDelete();
            $table->foreignId('guidance_field_id')->constrained('guidance_fields')->cascadeOnDelete();
            $table->foreignId('counselor_id')->constrained('counselors')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->foreignId('counseling_request_id')->nullable()->constrained('counseling_requests')->nullOnDelete();
            $table->foreignId('student_complaint_id')->nullable()->constrained('student_complaints')->nullOnDelete();
            $table->string('title');
            $table->enum('target_type', ['individual', 'kelompok', 'klasikal'])->default('individual');
            $table->foreignId('target_classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->foreignId('target_student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->unsignedInteger('participant_count')->default(1);
            $table->date('execution_date');
            $table->unsignedInteger('duration_minutes')->default(45);
            $table->string('location')->nullable();
            $table->text('activity_description');
            $table->text('evaluation_result')->nullable();
            $table->text('follow_up_plan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. Program Kerja BK Tahunan & Semester
        Schema::create('bk_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->nullOnDelete();
            $table->foreignId('service_type_id')->constrained('service_types')->cascadeOnDelete();
            $table->foreignId('guidance_field_id')->constrained('guidance_fields')->cascadeOnDelete();
            $table->string('name');
            $table->string('target_level', 50)->default('7,8,9');
            $table->unsignedTinyInteger('month_planned')->default(7);
            $table->text('goals');
            $table->text('evaluation_method')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. Rencana Pelaksanaan Layanan BK (RPL)
        Schema::create('bk_rpls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bk_program_id')->nullable()->constrained('bk_programs')->nullOnDelete();
            $table->foreignId('counselor_id')->constrained('counselors')->cascadeOnDelete();
            $table->foreignId('service_type_id')->constrained('service_types')->cascadeOnDelete();
            $table->foreignId('guidance_field_id')->constrained('guidance_fields')->cascadeOnDelete();
            $table->string('topic');
            $table->string('target_class', 100);
            $table->string('allocation_time', 100)->default('1 x 45 Menit');
            $table->text('general_goals');
            $table->text('specific_goals')->nullable();
            $table->text('materials')->nullable();
            $table->string('methods')->nullable();
            $table->string('media')->nullable();
            $table->text('steps')->nullable();
            $table->text('evaluation')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bk_rpls');
        Schema::dropIfExists('bk_programs');
        Schema::dropIfExists('counseling_services');
        Schema::dropIfExists('student_complaints');
        Schema::dropIfExists('counseling_schedules');
        Schema::dropIfExists('counseling_request_histories');
        Schema::dropIfExists('counseling_requests');
    }
};
