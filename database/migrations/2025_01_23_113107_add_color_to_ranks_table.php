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
    Schema::table('ranks', function (Blueprint $table) {
        $table->string('color', 7)->nullable()->after('name'); // HEX színkód (pl. #FF0000)
    });
}

public function down()
{
    Schema::table('ranks', function (Blueprint $table) {
        $table->dropColumn('color');
    });
}
};
