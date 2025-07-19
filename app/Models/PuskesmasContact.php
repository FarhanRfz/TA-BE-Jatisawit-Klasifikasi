<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuskesmasContact extends Model
{
    use HasFactory;

    protected $table = 'puskesmas_contacts';
    protected $primaryKey = 'id_contacts';

    protected $fillable = [
        'jenis_kontak',
        'link_kontak',
    ];
}
