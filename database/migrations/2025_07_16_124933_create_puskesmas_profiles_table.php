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
        Schema::create('puskesmas_profile', function (Blueprint $table) {
            $table->id('id_profil');
            $table->string('foto_bersama', 255)->nullable(); // Path atau URL gambar
            $table->string('struktur_organisasi', 255)->nullable(); // Path atau URL gambar/PDF
            $table->string('peta_wilayah_kerja', 255)->nullable(); // Path atau URL gambar
            $table->string('judul', 100)->nullable();
            $table->text('deskripsi_profil')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('tujuan')->nullable();
            $table->string('motto_tatanilai', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puskesmas_profile');
    }
};
