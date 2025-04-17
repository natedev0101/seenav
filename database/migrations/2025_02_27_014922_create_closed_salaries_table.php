<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('closed_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('closed_case_id')->constrained('closed_cases')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('rank_name');
            $table->integer('rank_salary');
            $table->string('subdivision_name')->nullable();
            $table->integer('subdivision_salary')->default(0);
            $table->integer('reports_count');
            $table->integer('reports_salary');
            $table->integer('total_salary');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('closed_salaries');
    }
};
