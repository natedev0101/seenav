<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementHeartsTable extends Migration
{
    public function up()
    {
        Schema::create('announcement_hearts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained()->onDelete('cascade'); // Hivatkozás az announcements táblára
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Hivatkozás a users táblára
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('announcement_hearts');
    }
};
