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
        Schema::create('loan_master_details', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id')->unsigned();
            $table->integer('emp_code')->unsigned();
            $table->integer('loan_type_id')->unsigned();
            $table->integer('loan_id')->unsigned();
            $table->integer('payment_no')->nullable();
            $table->string('payment_date', 255)->nullable();
            $table->integer('begining_balance')->nullable();
            $table->integer('payment')->nullable();
            $table->integer('principal')->nullable();
            $table->integer('interest')->nullable();
            $table->integer('ending_balance')->nullable();
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
        Schema::dropIfExists('loan_master_details');
    }
};
