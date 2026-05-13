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
        Schema::create('job_detail', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('job_id')->index('fk_job_detail_job');
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->boolean('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_detail');
    }
};
