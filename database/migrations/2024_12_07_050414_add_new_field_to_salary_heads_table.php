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
        Schema::table('salary_heads', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('code');
            $table->boolean('pay_cut_hd')->default(0)->after('sal_deduct_if_absent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_heads', function (Blueprint $table) {
            //
        });
    }
};
