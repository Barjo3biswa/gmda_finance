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
        Schema::create('lic_process_data', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->string('emp_code');
            $table->decimal('amount', 10, 2);
            $table->integer('month');
            $table->integer('year');
            $table->integer('status')->comment('0:active,1:inactive');
            $table->dateTime('processed_at')->nullable();
            $table->bigInteger('processed_by_id')->unsigned()->nullable();
            $table->boolean('process_allowed')->default(true);
            // $table->timestamp('deleted_at')->nullable();
            // $table->timestamp('created_at')->nullable();
            // $table->timestamp('updated_at')->nullable();
            $table->bigInteger('policy_id')->unsigned()->nullable();
            $table->string('policy_no', 191)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lic_process_data');
    }
};
