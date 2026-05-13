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
        Schema::table('job_documentation', function (Blueprint $table) {
            $table->foreign(['job_assignment_id'], 'fk_job_assignment_job_documentation')->references(['id'])->on('job_assigment')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_documentation', function (Blueprint $table) {
            $table->dropForeign('fk_job_assignment_job_documentation');
        });
    }
};
