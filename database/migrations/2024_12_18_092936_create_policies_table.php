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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('policy_id', 191)->nullable();
            $table->string('policy_no', 191);
            $table->string('policy_name', 191)->nullable();
            $table->string('monthly_premium', 191);
            $table->string('employee_id', 191)->nullable();
            $table->string('employee_code', 191)->nullable();
            $table->string('employee_designation', 191)->nullable();
            $table->string('dependent_name', 191)->nullable();
            $table->string('amount', 191)->nullable();
            $table->integer('wef_month')->nullable();
            $table->integer('wef_year')->nullable(); // Fixed typo here
            $table->string('wef_yy_mm', 10)->nullable();
            $table->date('start_date')->nullable();
            $table->date('maturity_date')->nullable();
            $table->date('closing_date')->nullable();
            $table->string('closing_reason', 191)->nullable();
            $table->string('closing_month', 191)->nullable();
            $table->string('closing_year', 191)->nullable();
            $table->string('cls_yy_mm', 10)->nullable();
            $table->string('status', 191)->nullable();
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
        Schema::dropIfExists('policies');
    }
};
