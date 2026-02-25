<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variant_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained()->cascadeOnDelete();
            $table->string('attribute_name'); // e.g. 'size', 'color'
            $table->string('attribute_value'); // e.g. 'XL', 'Red'
            $table->timestamps();

            $table->index('attribute_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_options');
    }
};
