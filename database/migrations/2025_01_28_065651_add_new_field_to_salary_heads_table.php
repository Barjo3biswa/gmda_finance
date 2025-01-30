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
            $table->renameColumn('order', 'order_fld');
            $table->integer('is_substitute_head')->default(0)->after('pay_cut_hd');
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
