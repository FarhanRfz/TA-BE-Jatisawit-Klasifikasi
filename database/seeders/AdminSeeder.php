<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah akun admin sudah ada (opsional, untuk mencegah duplikat)
        $adminExists = User::where('username', 'admin')->first();

        if (!$adminExists) {
            User::create([
                'username' => 'adminpuskesmas1',
                'password' => Hash::make('Jatisawit1'), // Ganti dengan password yang kuat
                'email' => 'puskesmasjatisawitjtb@gmail.com',
                'nama_lengkap_orangtua' => 'Admin Puskesmas Jatisawit',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info('Akun admin telah dibuat dengan username: admin dan role: admin');
        } else {
            $this->command->info('Akun admin sudah ada, tidak ada perubahan.');
        }
    }
}