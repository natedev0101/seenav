<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('report_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->foreignId('partner_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Egy jelentéshez egy felhasználó csak egyszer lehet hozzárendelve
            $table->unique(['report_id', 'partner_id']);
        });

        // Töröljük a régi partner_id oszlopot a reports táblából
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropColumn('partner_id');
        });
    }

    public function down()
    {
        // Visszaállítjuk a partner_id oszlopot a reports táblában
        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('partner_id')->nullable()->constrained('users')->onDelete('set null');
        });

        Schema::dropIfExists('report_partners');
    }
};
