<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique();
            $table->string('type');
            $table->string('veh_id')->unique();
            $table->date('registration_expiry');
            $table->json('warnings')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('subdivision_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('rank_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        // Kapcsolótábla a járművek és tulajdonosok között
        Schema::create('vehicle_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['vehicle_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_user');
        Schema::dropIfExists('vehicles');
    }
};
