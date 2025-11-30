<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Tambah user kolaborator kedua
        User::firstOrCreate(
            ['email' => 'collab@example.com'],
            [
                'name' => 'Collaborator User',
                'password' => 'password', // di-hash otomatis via casts di model User
                'email_notifications' => false,
                'push_notifications' => false,
            ]
        );

        // Tambah user viewer
        User::firstOrCreate(
            ['email' => 'viewer@example.com'],
            [
                'name' => 'Viewer User',
                'password' => 'password',
                'email_notifications' => false,
                'push_notifications' => false,
            ]
        );
    }
}
