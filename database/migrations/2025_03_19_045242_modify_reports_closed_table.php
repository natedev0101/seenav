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
        Schema::table('reports_closed', function (Blueprint $table) {
            // Töröljük a type enum mezőt
            $table->dropColumn('type');
        });

        Schema::table('reports_closed', function (Blueprint $table) {
            // Hozzáadjuk az új type string mezőt
            $table->string('type')->after('suspect_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports_closed', function (Blueprint $table) {
            // Töröljük a type string mezőt
            $table->dropColumn('type');
        });

        Schema::table('reports_closed', function (Blueprint $table) {
            // Visszaállítjuk az eredeti type enum mezőt
            $table->enum('type', ['ELŐÁLLÍTÁS', 'IGAZOLTATÁS'])->after('suspect_name');
        });
    }
};
