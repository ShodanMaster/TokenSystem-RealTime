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
        Schema::create('counter_token', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counter_id')->constrained()->onDelete('cascade');
            $table->foreignId('token_id')->constrained()->onDelete('cascade');
            $table->integer('last_went')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counter_token_pivot');
    }
};
