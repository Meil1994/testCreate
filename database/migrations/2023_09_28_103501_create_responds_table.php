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
        Schema::create('responds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responder_id')->constrained('responders')->onDelete('cascade');
            $table->integer('question');
            $table->integer('answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responds');
    }
};
