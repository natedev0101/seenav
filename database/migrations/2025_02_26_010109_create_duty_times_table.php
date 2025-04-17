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
        Schema::create('duty_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('total_duration')->default(0); // másodpercekben
            $table->integer('total_pause_duration')->default(0); // másodpercekben
            $table->boolean('is_paused')->default(false);
            $table->boolean('is_weekly_closed')->default(false); // jelzi hogy át lett-e már mozgatva a lezárt táblába
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duty_times');
    }
};
