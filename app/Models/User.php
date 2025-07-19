<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Notifications\ResetPasswordCustomNotification;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'nama_lengkap_orangtua',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function classificationHistories()
    {
        return $this->hasMany(\App\Models\ClassificationHistory::class, 'id_users', 'id_users');
    }

    public function sendPasswordResetNotification($token)
{
    $url = 'http://localhost:5173/reset-password?token=' . $token;

    $this->notify(new ResetPasswordCustomNotification($url));
}
}
