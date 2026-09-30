<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('code', 50)->unique();

            $table->string('name', 150);

            $table->string('unit', 30);

            $table->decimal('price', 15, 2)
                ->default(0);

            $table->decimal('minimum_stock', 12, 3)
                ->default(0);

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->index('name');
            $table->index('category_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
