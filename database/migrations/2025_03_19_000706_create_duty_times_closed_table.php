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
        Schema::create('duty_times_closed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('started_at');
            $table->timestamp('ended_at');
            $table->integer('total_duration')->default(0); // másodpercekben
            $table->integer('total_pause_duration')->default(0); // másodpercekben
            $table->integer('week_number');
            $table->integer('year');
            $table->timestamps();
            
            // Indexek a gyorsabb lekérdezésekhez
            $table->index(['week_number', 'year']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duty_times_closed');
    }
};
