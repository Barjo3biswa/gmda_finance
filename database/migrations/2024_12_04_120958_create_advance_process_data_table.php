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
        Schema::create('advance_processes', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no');
            $table->unsignedBigInteger('advance_id');
            $table->unsignedBigInteger('loan_head_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('emp_code', 10);
            $table->decimal('amount', 10, 2);
            $table->integer('month');
            $table->integer('year');
            $table->boolean('status')->default(1)->comment('1=active, 0=inactive');
            $table->date('processed_at');
            $table->unsignedBigInteger('processed_by_id');
            $table->tinyInteger('process_allowed')->default(1)->comment('1=allowed, 0=not allowed');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_process_data');
    }
};
