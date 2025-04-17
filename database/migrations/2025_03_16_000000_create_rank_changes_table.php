<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rank_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('old_rank_id')->nullable();
            $table->unsignedBigInteger('new_rank_id');
            $table->unsignedBigInteger('changed_by')->nullable(); 
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('old_rank_id')->references('id')->on('ranks')->onDelete('set null');
            $table->foreign('new_rank_id')->references('id')->on('ranks')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('rank_changes');
    }
};
