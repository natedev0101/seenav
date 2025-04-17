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
        Schema::table('duty_times', function (Blueprint $table) {
            $table->foreignId('force_ended_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('force_end_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('duty_times', function (Blueprint $table) {
            $table->dropForeign(['force_ended_by']);
            $table->dropColumn(['force_ended_by', 'force_end_reason']);
        });
    }
};
