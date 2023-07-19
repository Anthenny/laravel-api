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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_number');
            $table->string('house_number');
            $table->string('additions');
            $table->string('postal_code');
            $table->decimal('total_price', 6, 2);
            $table->boolean('completed');
            $table->string('session_id');
            $table->json('products');
            $table->timestamps();
        });
    }

    // TODO KOPPEL USER ID AAN ORDER

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
