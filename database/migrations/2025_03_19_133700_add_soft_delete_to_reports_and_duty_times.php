<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('reports', 'deleted_by')) {
                $table->foreignId('deleted_by')->nullable()->constrained('users');
            }
            if (!Schema::hasColumn('reports', 'closing_metadata')) {
                $table->json('closing_metadata')->nullable();
            }
        });

        Schema::table('duty_times', function (Blueprint $table) {
            if (!Schema::hasColumn('duty_times', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('duty_times', 'deleted_by')) {
                $table->foreignId('deleted_by')->nullable()->constrained('users');
            }
            if (!Schema::hasColumn('duty_times', 'closing_metadata')) {
                $table->json('closing_metadata')->nullable();
            }
        });

        Schema::table('report_partners', function (Blueprint $table) {
            if (!Schema::hasColumn('report_partners', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('report_partners', 'deleted_by')) {
                $table->foreignId('deleted_by')->nullable()->constrained('users');
            }
            if (!Schema::hasColumn('report_partners', 'closing_metadata')) {
                $table->json('closing_metadata')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by', 'closing_metadata']);
        });

        Schema::table('duty_times', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by', 'closing_metadata']);
        });

        Schema::table('report_partners', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by', 'closing_metadata']);
        });
    }
};
