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
        Schema::create('salary_heads', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('code', 50);
            $table->string('pay_head', 50);
            $table->string('income_type', 50);
            $table->integer('percentage', )->nullable();
            $table->string('calculation_on', )->nullable();
            $table->boolean('sal_deduct_if_absent')->default(0);
            $table->string('status', 50)->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_heads');
    }
};
