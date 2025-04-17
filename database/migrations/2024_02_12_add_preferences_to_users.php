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
        if (!Schema::hasColumn('users', 'dashboard_preferences')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('dashboard_preferences')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'dashboard_preferences')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('dashboard_preferences');
            });
        }
    }
};
