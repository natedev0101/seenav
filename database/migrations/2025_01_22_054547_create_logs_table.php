<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id(); // Egyedi azonosító
            $table->string('action'); // Művelet típusa, pl. "Rang létrehozás"
            $table->text('details'); // Művelet részletei: username, charactername stb.
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Melyik felhasználó végezte
            $table->timestamps(); // Művelet időpontja
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
