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
        Schema::create('salary_ksses', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id')->nullable();
            $table->string('emp_code', 20)->nullable();
            $table->integer('loan_amount')->nullable();
            $table->integer('interest')->nullable();
            $table->integer('subscrptn')->nullable();
            $table->integer('recovery')->nullable();
            $table->integer('total')->nullable();
            $table->string('month', 20)->nullable();
            $table->integer('year')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_ksses');
    }
};
