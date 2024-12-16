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
        Schema::create('salary_masters', function (Blueprint $table) {
            $table->id();

            $table->integer('emp_id');
            $table->string('emp_code', 20);
            $table->string('emp_name', 100);
            $table->longText('emp_object')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('department')->nullable();
            $table->integer('designation_id')->nullable();
            $table->string('payband', 10)->nullable();
            $table->integer('sal_block_id');
            $table->string('month', 10)->nullable();
            $table->string('year', 10)->nullable();
            $table->string('total_days', 10)->nullable();
            $table->string('working_days', 10)->nullable();
            $table->double('gross');
            $table->double('deduction');
            $table->double('net');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_masters');
    }
};
