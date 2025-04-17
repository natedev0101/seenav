<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('service_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
           
                $table->boolean('is_on_duty')->default(false);
                $table->timestamp('service_start')->nullable();
                $table->timestamp('service_end')->nullable();
                $table->integer('service_time')->default(0);
        });
    }

    public function down() {
        Schema::dropIfExists('service_sessions');
    }
};
