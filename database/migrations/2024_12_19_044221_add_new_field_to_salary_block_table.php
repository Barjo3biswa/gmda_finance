<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('salary_blocks', function (Blueprint $table) {
            $table->string('is_attendance_processed', 10)->default('no')->after('is_finalized');
            $table->string('is_loan_processed', 10)->default('no')->after('is_attendance_processed');
            $table->string('is_lic_processed', 10)->default('no')->after('is_loan_processed');
            $table->string('is_kss_processed', 10)->default('no')->after('is_lic_processed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_block', function (Blueprint $table) {
            //
        });
    }
};
