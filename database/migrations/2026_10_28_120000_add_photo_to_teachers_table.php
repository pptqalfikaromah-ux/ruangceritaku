<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('teachers') && !Schema::hasColumn('teachers', 'photo')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->string('photo')->nullable()->after('email');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('teachers') && Schema::hasColumn('teachers', 'photo')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropColumn('photo');
            });
        }
    }
};
