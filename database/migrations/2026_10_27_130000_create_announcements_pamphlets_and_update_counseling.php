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
        // 1. Tambah kolom metode konseling & resolusi pada tabel counseling_requests
        Schema::table('counseling_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('counseling_requests', 'counseling_method')) {
                $table->enum('counseling_method', ['tatap_muka', 'online_meet'])
                    ->default('tatap_muka')
                    ->after('purpose');
            }
            if (!Schema::hasColumn('counseling_requests', 'resolution_notes')) {
                $table->text('resolution_notes')->nullable()->after('status_notes');
            }
            if (!Schema::hasColumn('counseling_requests', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('resolution_notes');
            }
        });

        // 2. Tambah kolom metode konseling & link meeting pada tabel counseling_schedules
        Schema::table('counseling_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('counseling_schedules', 'counseling_method')) {
                $table->enum('counseling_method', ['tatap_muka', 'online_meet'])
                    ->default('tatap_muka')
                    ->after('counselor_id');
            }
            if (!Schema::hasColumn('counseling_schedules', 'meeting_link')) {
                $table->string('meeting_link', 500)->nullable()->after('location');
            }
        });

        // 3. Tabel Pengumuman Terpusat (Announcements)
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->enum('target_role', ['all', 'murid', 'guru_bk', 'wali_kelas'])->default('all');
            $table->enum('category', ['penting', 'info', 'agenda', 'kegiatan'])->default('info');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });

        // 4. Tabel Pamflet Kreativitas Siswa (Student Pamphlets)
        Schema::create('student_pamphlets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path');
            $table->string('status', 50)->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_pamphlets');
        Schema::dropIfExists('announcements');

        Schema::table('counseling_schedules', function (Blueprint $table) {
            $table->dropColumn(['counseling_method', 'meeting_link']);
        });

        Schema::table('counseling_requests', function (Blueprint $table) {
            $table->dropColumn(['counseling_method', 'resolution_notes', 'completed_at']);
        });
    }
};
