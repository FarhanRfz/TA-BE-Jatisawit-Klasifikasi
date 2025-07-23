<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassificationHistory extends Model
{
    protected $table = 'classification_history';
    protected $primaryKey = 'id_ch';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_users',
        'nama_anak',
        'umur',
        'berat_badan',
        'tinggi_badan',
        'jenis_kelamin',
        'status_stunting',
        'deskripsi_status',
        'waktu_klasifikasi',
        'exported',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'umur' => 'integer',
        // 'berat_badan' => 'decimal:2',
        // 'tinggi_badan' => 'decimal:2',
        'waktu_klasifikasi' => 'datetime',
        'exported' => 'boolean',
    ];

    /**
     * Get the user that owns the classification history.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }


}