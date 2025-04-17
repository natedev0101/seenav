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
            $table->decimal('rank_salary', 10, 2);
            $table->string('subdivision_name');
            $table->decimal('subdivision_salary', 10, 2);
            $table->integer('reports_count');
            $table->decimal('reports_salary', 10, 2);
            $table->decimal('total_salary', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('closed_salaries');
    }
};
