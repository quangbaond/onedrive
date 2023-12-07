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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->text('content');
            $table->string('email_contact');
            $table->string('company_name');
            $table->string('province_code');
            $table->string('district_code');
            $table->foreignId('industry_id')->nullable()->constrained('industries');
            $table->integer('limit')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_new')->default(false);
            $table->boolean('published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
