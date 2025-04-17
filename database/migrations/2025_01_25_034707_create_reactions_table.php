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
    Schema::create('reactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('announcement_id')->constrained('announcements')->onDelete('cascade'); // Közlemény azonosító
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Reagáló felhasználó
        $table->string('emoji'); // Az emoji, amivel reagált
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
