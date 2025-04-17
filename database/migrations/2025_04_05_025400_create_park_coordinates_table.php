<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('park_coordinates', function (Blueprint $table) {
            $table->id();
            $table->string('slot_id')->unique();
            $table->string('area_coords');  // "x1,y1,x2,y2" formátumban tároljuk
            $table->string('position');     // top, right, bottom, left
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('park_coordinates');
    }
};
