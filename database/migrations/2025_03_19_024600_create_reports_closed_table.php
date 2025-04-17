<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports_closed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('closed_week_id')->constrained('closed_weeks')->onDelete('cascade');
            $table->foreignId('original_report_id')->constrained('reports')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('suspect_name');
            $table->string('type');
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('report_date');
            $table->integer('week_number');
            $table->integer('year');
            $table->timestamps();
            
            // Indexek a gyorsabb lekérdezésekhez
            $table->index(['closed_week_id', 'user_id']);
            $table->index('report_date');
            $table->index(['week_number', 'year']);
        });

        // Létrehozzuk a report_partners_closed táblát is
        Schema::create('report_partners_closed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports_closed')->onDelete('cascade');
            $table->foreignId('partner_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Egyedi index, hogy ne lehessen ugyanazt a partnert többször hozzáadni
            $table->unique(['report_id', 'partner_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_partners_closed');
        Schema::dropIfExists('reports_closed');
    }
};
