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
        Schema::create('about_app', function (Blueprint $table) {
            $table->id('id_about');
            $table->string('logo_dinas', 255)->nullable(); // Path atau URL gambar
            $table->string('logo_puskesmas', 255)->nullable(); // Path atau URL gambar
            $table->string('nama_aplikasi', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_app');
    }
};
