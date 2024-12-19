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
        Schema::create('loan_process_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_id');
            $table->string('ref_no');
            $table->string('employee_id');
            $table->string('emp_code');
            $table->decimal('monthly_emi');
            $table->decimal('interest_installment')->nullable();
            $table->integer('month');
            $table->integer('year');
            $table->integer('type')->comment('loan,advance,lic');
            $table->integer('process_by');
            $table->date('process_date');
            $table->string('principal_or_interest');
            $table->integer('is_processed')->comment('0=not processed, 1=processed');
            $table->string('processed_by');
            $table->string('ip_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_process_logs');
    }
};