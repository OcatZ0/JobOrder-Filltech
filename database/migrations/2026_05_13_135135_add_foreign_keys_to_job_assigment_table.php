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
        Schema::table('job_assigment', function (Blueprint $table) {
            $table->foreign(['job_detail_id'], 'fk_job_assignment_job_detail')->references(['id'])->on('job_detail')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['assigned_to'], 'fk_job_assignment_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_assigment', function (Blueprint $table) {
            $table->dropForeign('fk_job_assignment_job_detail');
            $table->dropForeign('fk_job_assignment_user');
        });
    }
};
