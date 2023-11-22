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
        Schema::create('cvs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->boolean('is_admin')->default(false);
            $table->string('phone')->nullable();
            $table->string('note')->nullable();
            $table->string('address')->nullable();
            $table->date('birthday')->nullable();
            $table->string('cv')->nullable();
            $table->string('industry')->nullable();
            $table->string('experience')->nullable();
            $table->string('salary')->nullable();
            $table->string('position')->nullable();
            $table->string('level')->nullable();
            $table->string('language')->nullable();
            $table->string('skill')->nullable();
            $table->string('avatar')->nullable();
            $table->dateTime('interview_time')->nullable();
            $table->string('interview_result')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cvs');
    }
};
