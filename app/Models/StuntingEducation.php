<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StuntingEducation extends Model
{
    use HasFactory;

    protected $table = 'stunting_education';
    protected $primaryKey = 'id_education';

    protected $fillable = [
        'judul',
        'deskripsi',
        'informasi_stunting',
    ];
}
