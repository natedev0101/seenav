<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('month');
            $table->integer('year');
            $table->integer('duty_time_minutes')->default(0);
            $table->integer('reports_count')->default(0);
            $table->integer('calculated_amount')->default(0);
            $table->json('calculation_details');
            $table->timestamp('closed_at');
            $table->foreignId('closed_by')->constrained('users');
            $table->timestamps();

            // Indexek a gyorsabb lekérdezéshez
            $table->index(['user_id', 'month', 'year']);
            $table->index('closed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_snapshots');
    }
};
