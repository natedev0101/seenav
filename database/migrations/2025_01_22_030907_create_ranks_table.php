<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->id(); // Rang ID
            $table->string('name', 25)->unique(); // Rang neve, max 25 karakter
            $table->timestamps();
        });
    
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('rank_id')->nullable()->after('id'); // Felhasználó rang ID-ja
            $table->foreign('rank_id')->references('id')->on('ranks')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rank_id']);
            $table->dropColumn('rank_id');
        });
    
        Schema::dropIfExists('ranks');
    }
};