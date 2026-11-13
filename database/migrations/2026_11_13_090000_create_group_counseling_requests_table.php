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
        if (!Schema::hasTable('group_counseling_requests')) {
            Schema::create('group_counseling_requests', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_number')->unique();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
                $table->foreignId('semester_id')->nullable()->constrained('semesters')->nullOnDelete();
                $table->enum('service_type', ['bimbingan_kelompok', 'konseling_kelompok'])->default('bimbingan_kelompok');
                $table->string('topic');
                $table->text('description');
                $table->date('preferred_date')->nullable();
                $table->string('preferred_session')->nullable();
                $table->enum('status', [
                    'menunggu_kelompok',
                    'dikelompokkan',
                    'dibatalkan'
                ])->default('menunggu_kelompok');
                $table->foreignId('group_counseling_id')->nullable()->constrained('group_counselings')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('group_counselings') && Schema::hasColumn('group_counselings', 'leader_student_id')) {
            Schema::table('group_counselings', function (Blueprint $table) {
                $table->unsignedBigInteger('leader_student_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_counseling_requests');
    }
};

