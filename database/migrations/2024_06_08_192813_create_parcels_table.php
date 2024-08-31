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
        Schema::create('parcels', function (Blueprint $table) {
            $table->id();
            $table->string('referenceNumber');
            $table->string('senderName');
            $table->longText('senderAddress');
            $table->string('senderContact');
            $table->string('receipientName');
            $table->longText('receipientAddress');
            $table->string('receipientContact');
            $table->integer('receipientType');
            $table->integer('fromBranchId');
            $table->integer('toBranchId');
            $table->string('weight');
            $table->string('height');
            $table->string('width');
            $table->string('length');
            $table->decimal('price', 8, 2);
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcels');
    }
};
