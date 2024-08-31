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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('memberId');
            $table->string('firstName');
            $table->string('middleName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('gender');
            $table->string('DOB')->nullable();
            $table->string('address')->nullable();
            $table->string('occupation')->nullable();
            $table->string('contact');
            $table->string('contactOne')->nullable();
            $table->string('email')->nullable();
            $table->longText('picture')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
