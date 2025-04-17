<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('duty_times_closed', function (Blueprint $table) {
            $table->integer('week_number')->after('ended_at');
            $table->integer('year')->after('week_number');
        });
    }

    public function down(): void
    {
        Schema::table('duty_times_closed', function (Blueprint $table) {
            $table->dropColumn(['week_number', 'year']);
        });
    }
};
