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
        Schema::create('rank_deletion_logs', function (Blueprint $table) {
            $table->id();
            $table->string('reason'); // Törlés indoka
            $table->timestamp('deleted_at'); // Törlés időpontja
            $table->string('deleted_by'); // Ki törölte
            $table->string('requested_by'); // Ki kérte
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('rank_deletion_logs');
    }
};
