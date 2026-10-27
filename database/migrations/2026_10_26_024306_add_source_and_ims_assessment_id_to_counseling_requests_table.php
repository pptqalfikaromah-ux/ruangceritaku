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
        Schema::table('counseling_requests', function (Blueprint $table) {
            $table->string('source', 20)->default('pribadi')->after('ticket_number'); // 'pribadi' atau 'ims'
            $table->foreignId('ims_assessment_id')->nullable()->after('student_id')->constrained('ims_assessments')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('counseling_requests', function (Blueprint $table) {
            $table->dropForeign(['ims_assessment_id']);
            $table->dropColumn(['source', 'ims_assessment_id']);
        });
    }
};
