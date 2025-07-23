<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordCustomNotification;
use App\Models\ClassificationHistory;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    
    public function classificationHistory()
    {
        return $this->hasMany(ClassificationHistory::class, 'id_users', 'id_users');
    }
    
    public function sendPasswordResetNotification($token)
    {
        $url = 'http://localhost:5173/reset-password?token=' . $token;
    
        $this->notify(new ResetPasswordCustomNotification($url));
    }
    protected $table = 'users';
    protected $primaryKey = 'id_users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'nama_lengkap_orangtua',
        'role',
        'otp_code',
        'otp_expires_at',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
