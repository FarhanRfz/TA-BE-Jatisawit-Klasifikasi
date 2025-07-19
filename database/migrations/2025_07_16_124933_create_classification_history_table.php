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
        Schema::create('classification_history', function (Blueprint $table) {
            $table->id('id_ch');
            $table->unsignedBigInteger('id_users');
            $table->string('nama_anak', 100);
            $table->integer('umur');
            $table->decimal('berat_badan', 5, 2);
            $table->decimal('tinggi_badan', 5, 2);
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']); // Sesuai dengan input FastAPI
            $table->enum('status_stunting', ['stunting', 'normal']); // Sesuai hasil FastAPI (Stunted/Normal)
            $table->text('deskripsi_status');
            $table->dateTime('waktu_klasifikasi');
            $table->boolean('exported')->default(false); // Perbaiki typo menjadi 'exported' jika perlu
            $table->timestamps();

            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classification_history');
    }
};
