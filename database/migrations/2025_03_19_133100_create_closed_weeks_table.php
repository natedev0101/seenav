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
        Schema::create('closed_weeks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('closed_by')->constrained('users')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('week_number');
            $table->integer('year');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            // Indexek a gyorsabb lekérdezésekhez
            $table->index(['start_date', 'end_date']);
            $table->index(['week_number', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('closed_weeks');
    }
};
