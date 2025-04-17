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
        Schema::create('tax_companys', function (Blueprint $table) {
            $table->id();
            $table->string('taxnumber');
            $table->string('companyname');
            $table->string('activity');
            $table->string('marking');
            $table->integer('interiorid');
            $table->string('owner');
            $table->integer('charid');
            $table->string('forum');
            $table->date('tax');
            $table->boolean('closed')->default(false);
            $table->boolean('idgclosed')->default(false);
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('tax_acs', function (Blueprint $table) {
            $table->id();
            $table->string('taxnumber');
            $table->string('status');
            $table->string('comment');
            $table->date('date');
            $table->timestamps();
        });

        Schema::create('tax_administration', function (Blueprint $table) {
            $table->id();
            $table->string('taxnumber');
            $table->string('companyname');
            $table->bigInteger('amount');
            $table->string('reason');
            $table->string('administrator');
            $table->string('proof');
            $table->date('date');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('tax_tax', function (Blueprint $table) {
            $table->id();
            $table->string('taxnumber');
            $table->string('companyname');
            $table->timestamps();
        });

        Schema::create('tax_plate', function (Blueprint $table) {
            $table->id();
            $table->string('taxnumber');
            $table->string('companyname');
            $table->timestamps();
        });

        Schema::create('tax_interior', function (Blueprint $table) {
            $table->id();
            $table->string('taxnumber');
            $table->string('companyname');
            $table->timestamps();
        });

        Schema::create('tax_employee', function (Blueprint $table) {
            $table->id();
            $table->string('taxnumber');
            $table->string('companyname');
            $table->timestamps();
        });
    }
};
