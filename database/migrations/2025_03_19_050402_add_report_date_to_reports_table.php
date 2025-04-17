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
        Schema::table('reports', function (Blueprint $table) {
            // Töröljük a type enum mezőt
            $table->dropColumn('type');
        });

        Schema::table('reports', function (Blueprint $table) {
            // Hozzáadjuk az új type string mezőt
            $table->string('type')->after('suspect_name');
            // Hozzáadjuk a report_date mezőt, ha még nem létezik
            if (!Schema::hasColumn('reports', 'report_date')) {
                $table->date('report_date')->after('handled_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Töröljük a type string mezőt
            $table->dropColumn('type');
        });

        Schema::table('reports', function (Blueprint $table) {
            // Visszaállítjuk az eredeti type enum mezőt
            $table->enum('type', ['ELŐÁLLÍTÁS', 'IGAZOLTATÁS'])->after('suspect_name');
            // Töröljük a report_date mezőt
            if (Schema::hasColumn('reports', 'report_date')) {
                $table->dropColumn('report_date');
            }
        });
    }
};
