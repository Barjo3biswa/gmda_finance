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
            $table->integer('emp_id')->nullable();
            $table->integer('emp_code')->nullable();
            $table->integer('loan_id')->nullable();
            $table->integer('inst_no')->nullable();
            $table->integer('principal_installment')->nullable();
            $table->integer('interest_installment')->nullable();
            $table->decimal('principal_amount', 10, 2)->nullable();
            $table->decimal('interest_amount', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->integer('loan_type_id')->nullable();
            $table->string('recovery_type', 255)->nullable();
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            $table->integer('sal_block_id')->nullable();
            $table->string('status', 255)->nullable();
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
