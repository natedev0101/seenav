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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('report_count')->default(0); // Összes jelentés (függő, elfogadott, elutasított)
            $table->integer('pending_reports')->default(0); // Függőben lévő jelentések
            $table->integer('approved_reports')->default(0); // Elfogadott jelentések
            $table->integer('declined_reports')->default(0); // Elutasított jelentések
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('report_count');
            $table->dropColumn('pending_reports');
            $table->dropColumn('approved_reports');
            $table->dropColumn('declined_reports');
        });
    }
};
