<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salaries_closed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('closed_week_id')->constrained('closed_weeks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('minutes')->default(0);
            $table->integer('report_count')->default(0);
            $table->integer('merkur_count')->default(0);
            $table->integer('ado_count')->default(0);
            $table->integer('knyf_count')->default(0);
            $table->integer('beo_count')->default(0);
            $table->integer('sanitec_count')->default(0);
            $table->integer('top_report_count')->default(0);
            $table->decimal('base_salary', 10, 2)->default(0);
            $table->decimal('total_salary', 10, 2)->default(0);
            $table->boolean('is_paid')->default(false);
            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            // Egy héten belül egy felhasználónak csak egy fizetése lehet
            $table->unique(['closed_week_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries_closed');
    }
};
