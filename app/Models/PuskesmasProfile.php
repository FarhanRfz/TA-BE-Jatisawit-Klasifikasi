<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuskesmasProfile extends Model
{
    use HasFactory;

    protected $table = 'puskesmas_profile';
    protected $primaryKey = 'id_profil';

    protected $fillable = [
        'foto_bersama',
        'struktur_organisasi',
        'peta_wilayah_kerja',
        'judul',
        'deskripsi_profil',
        'visi',
        'misi',
        'tujuan',
        'motto_tatanilai',
    ];
}
