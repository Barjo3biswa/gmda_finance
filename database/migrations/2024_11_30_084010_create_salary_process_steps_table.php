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
        Schema::create('salary_process_steps', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('order');
            $table->string('route', 100);
            $table->integer('block_id');
            $table->string('status', 20)->default('underprocess');
            $table->integer('reprocess_count');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_process_steps');
    }
};
