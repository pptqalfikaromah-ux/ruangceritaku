<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counselors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nip_nuptk', 30)->nullable();
            $table->string('name');
            $table->string('title', 50)->nullable(); // Gelar akademik (S.Pd., Kons., dll.)
            $table->enum('gender', ['L', 'P']);
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('counselor_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counselor_id')->constrained('counselors')->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->boolean('is_primary')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Cegah duplikasi: satu rombel hanya punya satu guru BK utama per semester
            $table->unique(['classroom_id', 'semester_id'], 'cr_sem_counselor_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counselor_assignments');
        Schema::dropIfExists('counselors');
    }
};
