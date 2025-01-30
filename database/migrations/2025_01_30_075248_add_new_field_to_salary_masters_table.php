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
        Schema::table('salary_masters', function (Blueprint $table) {
            $table->string('ifsc_code', 50)->nullable()->after('designation_id');
            $table->string('account_no', 50)->nullable()->after('ifsc_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_masters', function (Blueprint $table) {
            //
        });
    }
};
