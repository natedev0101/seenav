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
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('closed_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('closed_at');
            $table->enum('status', ['draft', 'closed'])->default('draft');
            $table->timestamps();
            
            // Indexek a gyorsabb lekérdezésekhez
            $table->index(['start_date', 'end_date']);
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
