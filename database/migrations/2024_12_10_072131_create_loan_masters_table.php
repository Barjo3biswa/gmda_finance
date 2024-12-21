<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\SoftDeletes;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loan_masters', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 20);
            $table->integer('user_id')->nullable();
            $table->string('emp_code', 11)->nullable();
            $table->integer('loan_type_id')->nullable();
            $table->decimal('loan_amount', 28, 2)->nullable();
            $table->decimal('loan_interest_rate', 12, 2)->nullable();
            $table->decimal('principal_amount', 28, 2)->nullable();
            $table->decimal('outstanding_principal', 28, 2)->nullable();
            $table->integer('no_of_installment')->nullable();
            $table->integer('principal_installment')->nullable();
            $table->decimal('monthly_emi', 28, 2)->nullable();
            $table->decimal('adj_emi', 28, 2)->nullable();
            $table->string('adj_emi_in', 50)->nullable();
            $table->decimal('interest_amount', 28, 2)->nullable();
            $table->decimal('outstanding_interest_amount', 28, 2)->nullable();
            $table->integer('no_of_installment_interest')->nullable();
            $table->integer('interest_installment')->nullable();
            $table->decimal('adj_interest_emi', 28, 2)->nullable();
            $table->string('adj_interest_emi_in', 50)->nullable();
            $table->string('applied_on', 52)->nullable();
            $table->string('applied_for', 50)->nullable();
            $table->integer('fld_deptid')->nullable();
            $table->integer('fld_desigid')->nullable();
            $table->string('from_yyyy', 20)->nullable();
            $table->string('from_mm', 20)->nullable();
            $table->string('to_yyyy', 20)->nullable();
            $table->string('to_mm', 20)->nullable();
            $table->string('temp_status', 20)->nullable();
            $table->string('forwarded_by', 50)->nullable();
            $table->string('forwarded_on', 50)->nullable();
            $table->string('remarks', 50)->nullable();
            $table->integer('sal_block_id')->nullable();
            $table->integer('sal_block_month')->nullable();
            $table->integer('sal_block_yr')->nullable();
            $table->string('close_advance', 20)->nullable();
            $table->integer('closed_from_month')->nullable();
            $table->integer('closed_from_year')->nullable();
            $table->integer('closed_to_month')->nullable();
            $table->integer('closed_to_year')->nullable();
            $table->string('principal_instllmnt_status')->nullable();
            $table->string('intrst_instllmnt_status')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('loan_masters');
    }
};
