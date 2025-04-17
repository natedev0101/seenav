<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('park_data', function (Blueprint $table) {
            $table->string('owner')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('park_data', function (Blueprint $table) {
            $table->string('owner')->nullable(false)->change();
        });
    }
};
