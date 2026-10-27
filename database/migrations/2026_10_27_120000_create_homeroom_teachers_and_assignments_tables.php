<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Dukung role 'wali_kelas' di tabel users
        // Ubah enum kolom role menjadi string atau enum yang memuat wali_kelas
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru_bk', 'wali_kelas', 'murid') NOT NULL DEFAULT 'murid'");
        } catch (\Throwable $e) {
            // Fallback jika database bukan MySQL murni
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 30)->default('murid')->change();
            });
        }

        // 2. Tabel Data Wali Kelas (Teachers)
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nip_nuptk', 30)->nullable()->unique();
            $table->string('name');
            $table->enum('gender', ['L', 'P'])->default('L');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Tabel Penugasan Wali Kelas ke Rombel
        Schema::create('homeroom_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['academic_year_id', 'semester_id', 'classroom_id'], 'homeroom_rombel_unique');
        });

        // 4. Tabel Aduan Kasus Murid oleh Wali Kelas untuk Admin/Guru BK
        Schema::create('homeroom_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->nullOnDelete();
            $table->date('incident_date');
            $table->enum('category', [
                'kedisiplinan',
                'pelanggaran_aturan',
                'akademik',
                'sosial_pertemanan',
                'perilaku_sikap',
                'bullying_konflik',
                'lainnya'
            ])->default('kedisiplinan');
            $table->string('title');
            $table->text('description');
            $table->text('initial_action')->nullable();
            $table->text('teacher_notes'); // Catatan / rekomendasi wali kelas untuk Admin / Guru BK
            $table->enum('status', [
                'menunggu_tindak_lanjut',
                'sedang_ditangani',
                'selesai'
            ])->default('menunggu_tindak_lanjut');
            $table->text('counselor_response')->nullable();
            $table->foreignId('handled_by_counselor_id')->nullable()->constrained('counselors')->nullOnDelete();
            $table->dateTime('handled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homeroom_complaints');
        Schema::dropIfExists('homeroom_assignments');
        Schema::dropIfExists('teachers');

        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru_bk', 'murid') NOT NULL DEFAULT 'murid'");
        } catch (\Throwable $e) {
            // Ignore
        }
    }
};
