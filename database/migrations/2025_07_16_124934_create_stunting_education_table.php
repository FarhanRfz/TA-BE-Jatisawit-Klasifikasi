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
        Schema::create('stunting_education', function (Blueprint $table) {
            $table->id('id_education');
            $table->string('judul', 100);
            $table->text('deskripsi');
            $table->text('informasi_stunting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stunting_education');
    }
};
