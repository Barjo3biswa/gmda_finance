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
        Schema::create('loan_recoveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emp_id')->nullable();
            $table->unsignedBigInteger('emp_code')->nullable();
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->unsignedInteger('inst_no')->nullable();
            $table->unsignedInteger('principal_installment')->nullable();
            $table->unsignedInteger('interest_installment')->nullable();
            $table->decimal('interest_amount', 10, 2)->nullable();
            $table->unsignedBigInteger('loan_type_id')->nullable();
            $table->string('recovery_type', 255)->nullable();
            $table->integer('month', 255)->nullable();
            $table->integer('year', 255)->nullable();
            $table->unsignedInteger('status')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_recoveries');
    }
};
