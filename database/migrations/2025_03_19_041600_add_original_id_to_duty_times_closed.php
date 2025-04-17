<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('duty_times_closed', function (Blueprint $table) {
            $table->foreignId('original_duty_time_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('duty_times_closed', function (Blueprint $table) {
            $table->dropColumn('original_duty_time_id');
        });
    }
};
