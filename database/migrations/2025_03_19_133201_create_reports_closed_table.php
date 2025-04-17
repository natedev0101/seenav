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
        Schema::create('reports_closed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('closed_week_id')->constrained('closed_weeks')->onDelete('cascade');
            $table->foreignId('original_report_id')->nullable()->constrained('reports')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('suspect_name');
            $table->string('type');
            $table->integer('fine_amount')->nullable();
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->string('status');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('report_date');
            $table->integer('week_number');
            $table->integer('year');
            $table->timestamps();
            
            // Indexek a gyorsabb lekérdezésekhez
            $table->index(['closed_week_id']);
            $table->index(['user_id']);
            $table->index(['report_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports_closed');
    }
};
