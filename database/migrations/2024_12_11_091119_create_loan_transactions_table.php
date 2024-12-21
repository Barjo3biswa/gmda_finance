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
        Schema::create('loan_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 20);
            $table->integer('user_id');
            $table->integer('loan_type_id');
            $table->float('loan_ammount', 10, 2)->nullable();
            $table->integer('outstanding_principal')->nullable();
            $table->integer('no_of_instalment')->nullable();
            $table->float('emi', 10, 2)->nullable();
            $table->bigInteger('f_installment')->nullable();
            $table->float('interest_amount', 10, 2)->nullable();
            $table->integer('outstanding_interest_amount')->nullable();
            $table->float('pricipal_ammount', 10, 2)->nullable();
            $table->integer('no_of_instalment_interest')->nullable();
            $table->integer('f_i_installment')->nullable();
            $table->float('interest_emi', 10, 2)->nullable();
            $table->date('applied_on')->nullable();
            $table->string('applied_for', 200)->nullable();
            $table->integer('fld_DeptID')->nullable();
            $table->integer('status');
            $table->string('forwarded_by', 40)->nullable();
            $table->date('forwarded_on')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('principal_installment')->default(0);
            $table->integer('interest_installment')->default(0);
            $table->integer('sal_block_id')->nullable();
            $table->integer('sal_block_month')->nullable();
            $table->integer('sal_block_yr')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_transactions');
    }
};
