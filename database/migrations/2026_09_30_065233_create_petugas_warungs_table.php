<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petugas_warungs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('petugas_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('warung_id')
                ->constrained('warungs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['petugas_id', 'warung_id']);

            $table->index('petugas_id');
            $table->index('warung_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas_warungs');
    }
};
