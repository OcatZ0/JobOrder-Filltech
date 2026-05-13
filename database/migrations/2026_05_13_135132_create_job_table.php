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
        Schema::create('job', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('number_of_the_day');
            $table->integer('job_classification_id')->index('fk_job_job_classification');
            $table->integer('created_by')->index('fk_job_created_by');
            $table->string('job_description', 200);
            $table->dateTime('start_at');
            $table->integer('duration');
            $table->dateTime('end_at')->nullable();
            $table->enum('status', ['waiting_acc', 'on_progress', 'pending', 'done'])->default('waiting_acc');
            $table->string('pending_reason', 200)->nullable();
            $table->boolean('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job');
    }
};
