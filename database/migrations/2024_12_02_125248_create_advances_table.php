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
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 20);
            $table->unsignedBigInteger('advance_id');
            $table->unsignedBigInteger('user_id');
            $table->string('emp_code', 10);
            $table->integer('loan_head_id')->default(0)->comment('salary id');
            $table->decimal('principal_amount', 10, 2);
            $table->decimal('monthly_installment', 10, 2)->nullable();
            $table->decimal('interest_amount', 10, 2)->nullable();
            $table->decimal('interest_recovered', 10, 2)->nullable();
            $table->decimal('recovered_amount', 10, 2)->default(0.00);
            $table->integer('duration')->default(0);
            $table->string('installment_month', 10)->nullable();
            $table->integer('installment_year')->nullable();
            $table->date('installment_date_from')->nullable();
            $table->date('installment_date_to')->nullable();
            $table->decimal('adjustable_installment', 10, 2)->nullable();
            $table->string('adjust_in', 20)->nullable();
            $table->date('start_date')->nullable();
            $table->date('closing_date')->nullable();
            $table->integer('status')->nullable();
            $table->date('process_date')->nullable();
            $table->string('close_advance', 20)->nullable();
            $table->integer('closed_from_month')->nullable();
            $table->integer('closed_from_year')->nullable();
            $table->integer('closed_to_month')->nullable();
            $table->integer('closed_to_year')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advances');
    }
};
