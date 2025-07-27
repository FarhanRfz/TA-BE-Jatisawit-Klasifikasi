<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrtuAnak extends Model
{
    protected $table = 'ortu_anak';
    protected $primaryKey = 'id_ota';
    public $incrementing = true; // Pastikan id_ota auto-increment
    // protected $keyType = 'int'; // Tipe data primary key adalah integer

    protected $fillable = [
        'nama_ibu',
        'nama_bapak',
        'nama_anak',
        'jenis_kelamin_anak',
        'gol_darah_anak',
        'tanggal_lahir_anak',
        'pekerjaan_ibu',
        'alamat_rumah',
        'tanggal_daftar',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir_anak' => 'date',
        'tanggal_daftar' => 'date',
    ];
}