<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('recipient', 191);
            $table->string('amount', 191)->nullable(); // Allow null values for amount
            $table->text('payment_note')->nullable(); // Allow null values for payment_note
            $table->string('identifier', 191)->unique();
            $table->string('status', 191);
            $table->string('from', 191);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
