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
        Schema::create('similarities', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('nama_file');
            $table->decimal('persentase_kemiripan', 5, 2); // Decimal dengan 5 digit total dan 2 digit di belakang koma
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('similarities');
    }
};
