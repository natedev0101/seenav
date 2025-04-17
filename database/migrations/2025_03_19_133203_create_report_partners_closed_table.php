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
        Schema::create('report_partners_closed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports_closed')->onDelete('cascade');
            $table->foreignId('partner_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Indexek a gyorsabb lekérdezésekhez
            $table->index(['report_id']);
            $table->index(['partner_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_partners_closed');
    }
};
