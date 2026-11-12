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
        if (!Schema::hasTable('group_counselings')) {
            Schema::create('group_counselings', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_number')->unique();
                $table->enum('service_type', ['bimbingan_kelompok', 'konseling_kelompok'])->default('bimbingan_kelompok');
                $table->foreignId('leader_student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->foreignId('semester_id')->nullable()->constrained('semesters')->nullOnDelete();
                $table->string('topic');
                $table->text('description');
                $table->text('group_goal')->nullable();
                $table->date('preferred_date')->nullable();
                $table->string('preferred_session')->nullable();
                $table->enum('status', [
                    'menunggu_persetujuan',
                    'dijadwalkan',
                    'selesai',
                    'dibatalkan'
                ])->default('menunggu_persetujuan');
                $table->foreignId('counselor_id')->nullable()->constrained('counselors')->nullOnDelete();
                $table->date('scheduled_date')->nullable();
                $table->string('start_time', 10)->nullable();
                $table->string('end_time', 10)->nullable();
                $table->string('location')->nullable();
                $table->string('meeting_link')->nullable();
                $table->text('counselor_notes')->nullable();
                $table->text('evaluation_result')->nullable();
                $table->text('follow_up_plan')->nullable();
                $table->unsignedSmallInteger('duration_minutes')->nullable();
                $table->dateTime('completed_at')->nullable();
                $table->dateTime('read_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('group_counseling_members')) {
            Schema::create('group_counseling_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('group_counseling_id')->constrained('group_counselings')->cascadeOnDelete();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->string('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_counseling_members');
        Schema::dropIfExists('group_counselings');
    }
};

