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
        Schema::create('user_hold_unholds', function (Blueprint $table) {
            $table->id();
            $table->integer('emp_id');
            $table->string('emp_code', 20)->nullable();
            $table->string('type', 20)->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('holding_type', 20);
            $table->longText('holding reason')->nullable();
            $table->string('status', 20)->nullable();
            $table->string('created_by', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_hold_unholds');
    }
};
