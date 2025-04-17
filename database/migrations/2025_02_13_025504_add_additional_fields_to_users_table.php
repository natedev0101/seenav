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
            $table->string('character_id')->nullable()->after('charactername');
            $table->integer('played_minutes')->default(0)->after('character_id');
            $table->string('phone_number')->nullable()->after('played_minutes');
            $table->string('badge_number')->nullable()->after('phone_number');
            $table->string('recommended_by')->nullable()->after('badge_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'character_id',
                'played_minutes',
                'phone_number',
                'badge_number',
                'recommended_by'
            ]);
        });
    }
};
