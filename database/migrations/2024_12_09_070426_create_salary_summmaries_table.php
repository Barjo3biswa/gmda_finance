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
        Schema::create('salary_summmaries', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id');
            $table->string('emp_code', 20);
            $table->integer('sal_block_id');
            $table->string('month', 10)->nullable();
            $table->string('year', 10)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_summmaries');
    }
};
