<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah is_heavy (N.M.Y.B) pada ims_answers
        if (Schema::hasTable('ims_answers') && !Schema::hasColumn('ims_answers', 'is_heavy')) {
            Schema::table('ims_answers', function (Blueprint $table) {
                $table->boolean('is_heavy')->default(false)->after('is_selected');
            });
        }

        // 2. Tambah consult_to pada ims_assessments
        if (Schema::hasTable('ims_assessments') && !Schema::hasColumn('ims_assessments', 'consult_to')) {
            Schema::table('ims_assessments', function (Blueprint $table) {
                $table->string('consult_to')->nullable()->after('final_notes');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ims_answers') && Schema::hasColumn('ims_answers', 'is_heavy')) {
            Schema::table('ims_answers', function (Blueprint $table) {
                $table->dropColumn('is_heavy');
            });
        }

        if (Schema::hasTable('ims_assessments') && Schema::hasColumn('ims_assessments', 'consult_to')) {
            Schema::table('ims_assessments', function (Blueprint $table) {
                $table->dropColumn('consult_to');
            });
        }
    }
};
