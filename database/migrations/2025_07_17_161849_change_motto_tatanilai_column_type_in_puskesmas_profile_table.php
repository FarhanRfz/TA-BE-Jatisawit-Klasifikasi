<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMottoTatanilaiColumnTypeInPuskesmasProfileTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('puskesmas_profile', function (Blueprint $table) {
            $table->text('motto_tatanilai')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('puskesmas_profile', function (Blueprint $table) {
            $table->string('motto_tatanilai')->change(); // Kembalikan ke string default jika rollback
        });
    }
}
