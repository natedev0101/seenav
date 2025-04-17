<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_on_duty')->default(false)->after('is_officer'); // Jelzi, hogy a felhasználó szolgálatban van-e
            $table->timestamp('service_start')->nullable()->after('is_on_duty'); // Szolgálat kezdete
            $table->timestamp('service_end')->nullable()->after('service_start'); // Szolgálat vége
            $table->integer('service_time')->default(0)->after('service_end'); // Összes szolgálatban eltöltött idő
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_on_duty', 'service_start', 'service_end', 'service_time']);
        });
    }
};
