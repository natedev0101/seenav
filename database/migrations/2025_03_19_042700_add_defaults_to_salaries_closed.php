<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salaries_closed', function (Blueprint $table) {
            $table->integer('minutes')->default(0)->change();
            $table->integer('report_count')->default(0)->change();
            $table->integer('merkur_count')->default(0)->change();
            $table->integer('ado_count')->default(0)->change();
            $table->integer('knyf_count')->default(0)->change();
            $table->integer('beo_count')->default(0)->change();
            $table->integer('sanitec_count')->default(0)->change();
            $table->integer('top_report_count')->default(0)->change();
            $table->integer('base_salary')->default(0)->change();
            $table->integer('total_salary')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('salaries_closed', function (Blueprint $table) {
            $table->integer('minutes')->change();
            $table->integer('report_count')->change();
            $table->integer('merkur_count')->change();
            $table->integer('ado_count')->change();
            $table->integer('knyf_count')->change();
            $table->integer('beo_count')->change();
            $table->integer('sanitec_count')->change();
            $table->integer('top_report_count')->change();
            $table->integer('base_salary')->change();
            $table->integer('total_salary')->change();
        });
    }
};
