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
        if (Schema::hasTable('student_complaints') && !Schema::hasColumn('student_complaints', 'read_at')) {
            Schema::table('student_complaints', function (Blueprint $table) {
                $table->timestamp('read_at')->nullable();
            });
        }

        if (Schema::hasTable('counseling_requests') && !Schema::hasColumn('counseling_requests', 'read_at')) {
            Schema::table('counseling_requests', function (Blueprint $table) {
                $table->timestamp('read_at')->nullable();
            });
        }

        if (Schema::hasTable('homeroom_complaints') && !Schema::hasColumn('homeroom_complaints', 'read_at')) {
            Schema::table('homeroom_complaints', function (Blueprint $table) {
                $table->timestamp('read_at')->nullable();
            });
        }

        if (Schema::hasTable('ims_assessments') && !Schema::hasColumn('ims_assessments', 'read_at')) {
            Schema::table('ims_assessments', function (Blueprint $table) {
                $table->timestamp('read_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('student_complaints') && Schema::hasColumn('student_complaints', 'read_at')) {
            Schema::table('student_complaints', function (Blueprint $table) {
                $table->dropColumn('read_at');
            });
        }

        if (Schema::hasTable('counseling_requests') && Schema::hasColumn('counseling_requests', 'read_at')) {
            Schema::table('counseling_requests', function (Blueprint $table) {
                $table->dropColumn('read_at');
            });
        }

        if (Schema::hasTable('homeroom_complaints') && Schema::hasColumn('homeroom_complaints', 'read_at')) {
            Schema::table('homeroom_complaints', function (Blueprint $table) {
                $table->dropColumn('read_at');
            });
        }

        if (Schema::hasTable('ims_assessments') && Schema::hasColumn('ims_assessments', 'read_at')) {
            Schema::table('ims_assessments', function (Blueprint $table) {
                $table->dropColumn('read_at');
            });
        }
    }
};
