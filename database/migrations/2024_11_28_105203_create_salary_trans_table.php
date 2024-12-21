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
        Schema::create('salary_trans', function (Blueprint $table) {
            $table->id();

            $table->integer('emp_id');
            $table->string('emp_code', 20);
            $table->integer('sal_head_id');
            $table->string('salary_head_code', 50);
            $table->string('salary_head_name', 100);
            $table->integer('month');
            $table->integer('year');
            $table->integer('block_id');
            $table->double('working_days')->default(30);
            $table->string('pay_head', 10);
            $table->double('amount');
            $table->double('last_amount');
            $table->string('status', 10)->nullable();
            $table->string('remarks', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_trans');
    }
};
