<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports_closed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('closed_week_id')->constrained('closed_weeks')->onDelete('cascade');
            $table->foreignId('original_report_id')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->string('suspect_name');
            $table->string('type');
            $table->integer('fine_amount')->nullable();
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->string('status');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users');
            $table->timestamp('report_date');
            $table->timestamps();
        });

        Schema::create('report_partners_closed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports_closed')->onDelete('cascade');
            $table->foreignId('partner_id')->constrained('partners');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_partners_closed');
        Schema::dropIfExists('reports_closed');
    }
};
