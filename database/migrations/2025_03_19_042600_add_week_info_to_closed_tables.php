<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports_closed', function (Blueprint $table) {
            $table->integer('week_number')->default(1)->after('report_date');
            $table->integer('year')->default(date('Y'))->after('week_number');
        });

        Schema::table('salaries_closed', function (Blueprint $table) {
            $table->integer('week_number')->default(1)->after('total_salary');
            $table->integer('year')->default(date('Y'))->after('week_number');
        });

        Schema::table('duty_times_closed', function (Blueprint $table) {
            $table->integer('week_number')->default(1)->after('ended_at');
            $table->integer('year')->default(date('Y'))->after('week_number');
        });
    }

    public function down(): void
    {
        Schema::table('reports_closed', function (Blueprint $table) {
            $table->dropColumn(['week_number', 'year']);
        });

        Schema::table('salaries_closed', function (Blueprint $table) {
            $table->dropColumn(['week_number', 'year']);
        });

        Schema::table('duty_times_closed', function (Blueprint $table) {
            $table->dropColumn(['week_number', 'year']);
        });
    }
};
