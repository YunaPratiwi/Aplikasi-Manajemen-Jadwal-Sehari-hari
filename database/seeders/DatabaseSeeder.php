<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a demo user if not exists
        $user = User::firstOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User',
                // Will be hashed automatically by cast in User model
                'password' => 'password',
                'email_notifications' => false,
                'push_notifications' => false,
            ]
        );

        // Tambah user kolaborator kedua
        $this->call(UserSeeder::class);

        // Jalankan seeder kategori dan tugas dasar
        $this->call(CategorySeeder::class);
        $this->call(TaskSeeder::class);

        // Lengkapi data tugas (tags & reminder)
        $this->call(TaskEnhancementSeeder::class);

        // Tambah kolaborator pada setiap tugas demo
        $this->call(TaskCollaboratorSeeder::class);

        // Tambah kolaborator viewer pada sebagian tugas
        $this->call(TaskViewerCollaboratorSeeder::class);

        // Tandai sebagian tugas sebagai completed
        $this->call(TaskCompletionSeeder::class);

        // Tambah viewer pada 2 tugas lagi
        $this->call(TaskViewerMoreCollaboratorSeeder::class);

        // Tandai satu tugas cancelled
        $this->call(TaskCancelledSeeder::class);

        // Isi attachments dan time tracking
        $this->call(TaskAttachmentsSeeder::class);
    }
}
