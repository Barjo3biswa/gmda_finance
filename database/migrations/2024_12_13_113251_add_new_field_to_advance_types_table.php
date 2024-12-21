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
        Schema::table('advance_types', function (Blueprint $table) {
            $table->integer('int_salary_head_id')->nullable()->after('salary_head_id');
            $table->string('advance_type', 10)->nullable()->after('int_salary_head_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advance_types', function (Blueprint $table) {
            //
        });
    }
};
