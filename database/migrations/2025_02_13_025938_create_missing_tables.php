<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Admin logs tábla
        if (!Schema::hasTable('admin_logs')) {
            Schema::create('admin_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('didWhat');
                $table->timestamps();
            });
        }

        // Duty times tábla
        if (!Schema::hasTable('duty_times')) {
            Schema::create('duty_times', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->dateTime('begin');
                $table->dateTime('end');
                $table->integer('minutes');
                $table->timestamps();
            });
        }

        // Duty times closed tábla
        if (!Schema::hasTable('duty_times_closed')) {
            Schema::create('duty_times_closed', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->dateTime('begin');
                $table->dateTime('end');
                $table->integer('minutes');
                $table->timestamps();
            });
        }

        // Reports tábla
        if (!Schema::hasTable('reports')) {
            Schema::create('reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('content');
                $table->timestamps();
            });
        }

        // Reports closed tábla
        if (!Schema::hasTable('reports_closed')) {
            Schema::create('reports_closed', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('content');
                $table->timestamps();
            });
        }

        // Users closed tábla
        if (!Schema::hasTable('users_closed')) {
            Schema::create('users_closed', function (Blueprint $table) {
                $table->id();
                $table->string('charactername');
                $table->string('username');
                $table->string('password');
                $table->boolean('isAdmin')->default(false);
                $table->boolean('canGiveAdmin')->default(false);
                $table->boolean('is_superadmin')->default(false);
                $table->string('character_id')->nullable();
                $table->integer('played_minutes')->default(0);
                $table->string('phone_number')->nullable();
                $table->string('badge_number')->nullable();
                $table->string('recommended_by')->nullable();
                $table->timestamps();
            });
        }

        // Variables tábla
        if (!Schema::hasTable('variables')) {
            Schema::create('variables', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->text('value');
                $table->timestamps();
            });
        }

        // Locks tábla
        if (!Schema::hasTable('locks')) {
            Schema::create('locks', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->boolean('isLocked')->default(false);
                $table->timestamps();
            });
        }

        // Inactivity tábla
        if (!Schema::hasTable('inactivity')) {
            Schema::create('inactivity', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->dateTime('begin');
                $table->dateTime('end');
                $table->string('reason');
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('admin_logs');
        Schema::dropIfExists('duty_times');
        Schema::dropIfExists('duty_times_closed');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('reports_closed');
        Schema::dropIfExists('users_closed');
        Schema::dropIfExists('variables');
        Schema::dropIfExists('locks');
        Schema::dropIfExists('inactivity');
    }
};
