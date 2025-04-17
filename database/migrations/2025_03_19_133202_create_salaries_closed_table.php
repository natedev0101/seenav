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
        Schema::create('salaries_closed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('closed_week_id')->constrained('closed_weeks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('minutes')->default(0);
            $table->integer('report_count')->default(0);
            $table->integer('merkur_count')->default(0);
            $table->integer('top_report_count')->default(0);
            $table->integer('base_salary')->default(0);
            $table->integer('total_salary')->default(0);
            $table->integer('week_number');
            $table->integer('year');
            $table->timestamps();
            
            // Indexek a gyorsabb lekérdezésekhez
            $table->index(['closed_week_id']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries_closed');
    }
};
