<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('two_factor_required')->default(false);
        });
        
        // Alapértelmezetten beállítjuk az admin és superadmin felhasználóknak
        DB::table('users')
            ->where('isAdmin', true)
            ->orWhere('is_superadmin', true)
            ->update(['two_factor_required' => true]);
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('two_factor_required');
        });
    }
};
