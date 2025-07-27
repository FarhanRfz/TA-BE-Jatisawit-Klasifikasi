<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrtuAnakTable extends Migration
{
    public function up()
    {
        Schema::create('ortu_anak', function (Blueprint $table) {
            $table->id('id_ota'); // Primary key dengan nama id_ota
            $table->string('nama_ibu'); // Nama ibu
            $table->string('nama_bapak'); // Nama bapak
            $table->string('nama_anak'); // Nama anak
            $table->enum('jenis_kelamin_anak', ['L', 'P']); // Jenis kelamin anak (Laki-laki/Perempuan)
            $table->string('gol_darah_anak')->nullable(); // Golongan darah anak, bisa null jika belum diketahui
            $table->date('tanggal_lahir_anak'); // tanggal lahir anak dalam format date
            $table->string('pekerjaan_ibu'); // Pekerjaan ibu
            $table->text('alamat_rumah'); // Alamat rumah dalam format teks
            $table->date('tanggal_daftar')->default(now()); // Tanggal daftar, default hari ini
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif'); // Status data
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('ortu_anak');
    }
}