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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 2048);
            $table->string('slug', 2048);
            $table->string('color', 2048);
            $table->string('thumbnail', 2048);
            $table->string('category', 2048);
            $table->integer('price');
            $table->integer('amount');
            $table->boolean('reserved');
            $table->integer('weight');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
