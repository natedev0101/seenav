<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('announcements', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // Közlemény címe
        $table->text('content'); // BB kódokkal és emojikkal formázott tartalom
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Létrehozó admin
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
