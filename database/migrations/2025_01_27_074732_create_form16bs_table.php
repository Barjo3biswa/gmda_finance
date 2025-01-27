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
        Schema::create('form16bs', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_code')->nullable();
            $table->string('financial_year')->nullable();
            $table->string('basic1')->nullable();
            $table->string('basic2')->nullable();
            $table->string('basic3')->nullable();
            $table->string('gross')->nullable();
            $table->string('hra')->nullable();
            $table->string('conveyance')->nullable();
            $table->string('special_allowance')->nullable();
            $table->string('charge_allowance')->nullable();
            $table->string('other')->nullable();
            $table->string('total_less')->nullable();
            $table->string('balance')->nullable();
            $table->string('entertainment_allowance')->nullable();
            $table->string('professional_tax')->nullable();
            $table->string('aggregate')->nullable();
            $table->string('income_chargeable')->nullable();
            $table->string('sb_interest')->nullable();
            $table->string('hbl_interest')->nullable();
            $table->string('total_other_income')->nullable();
            $table->string('gross_total_income')->nullable();
            $table->string('gpf_epf_cpf')->nullable();
            $table->string('gis')->nullable();
            $table->string('life_insurance_premium')->nullable();
            $table->string('public_provident_fund')->nullable();
            $table->string('repayment_of_hbl_principal')->nullable();
            $table->string('tuition_fee_of_children')->nullable();
            $table->string('other_deduction')->nullable();
            $table->string('section_80ccc')->nullable();
            $table->string('section_80ccd')->nullable();
            $table->string('total_deduction_9')->nullable();
            $table->string('medical_insurance_premium')->nullable();
            $table->string('medical_treatment_of_handicapped')->nullable();
            $table->string('interest_on_higher_edu_loan')->nullable();
            $table->string('donations')->nullable();
            $table->string('interest_on_sb_ac_deducation')->nullable();
            $table->string('aggregate_of_deductible_amount')->nullable();
            $table->string('total_income')->nullable();
            $table->string('tax_on_total_income')->nullable();
            $table->string('rebate')->nullable();
            $table->string('edu_cess')->nullable();
            $table->string('tax_payable')->nullable();
            $table->string('total_tax_payable')->nullable();
            $table->string('less_relief')->nullable();
            $table->string('tax_payable18')->nullable();
            $table->string('tax_deduct')->nullable();
            $table->string('balance_20')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form16bs');
    }
};
