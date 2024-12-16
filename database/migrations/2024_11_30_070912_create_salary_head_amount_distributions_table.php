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
        Schema::create('salary_head_amount_distributions', function (Blueprint $table) {
            $table->id();

            $table->integer('emp_id');
            $table->string('emp_code', 20);
            $table->integer('sal_head_id');
            $table->string('salary_head_code', 50);
            $table->string('salary_head_name', 100);
            $table->string('pay_head', 10);
            $table->double('amount');
            $table->string('status', 10)->nullable();
            $table->string('remarks', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_head_amount_distributions');
    }
};
