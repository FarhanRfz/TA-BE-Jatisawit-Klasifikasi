<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutApp extends Model
{
    use HasFactory;

    protected $table = 'about_app';
    protected $primaryKey = 'id_about';

    protected $fillable = [
        'logo_dinas',
        'logo_puskesmas',
        'nama_aplikasi',
    ];
}
