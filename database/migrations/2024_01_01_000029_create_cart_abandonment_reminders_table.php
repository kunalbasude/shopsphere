<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_abandonment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('channel', ['email', 'push']);
            $table->integer('attempt')->default(1);
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->index(['cart_id', 'channel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_abandonment_reminders');
    }
};
