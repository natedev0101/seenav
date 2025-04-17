<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('duty_times_closed', function (Blueprint $table) {
            $table->integer('total_duration')->default(0)->after('ended_at');
            $table->integer('total_pause_duration')->default(0)->after('total_duration');
        });
    }

    public function down(): void
    {
        Schema::table('duty_times_closed', function (Blueprint $table) {
            $table->dropColumn('total_duration');
            $table->dropColumn('total_pause_duration');
        });
    }
};
