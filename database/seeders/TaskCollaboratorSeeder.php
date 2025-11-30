<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskCollaboratorSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'demo@example.com')->first();
        $collab = User::where('email', 'collab@example.com')->first();

        if (!$owner || !$collab) {
            return; // Pastikan UserSeeder & DatabaseSeeder sudah membuat user
        }

        // Ambil semua tugas milik owner dan tambahkan collab sebagai editor
        Task::where('user_id', $owner->id)->get()->each(function ($task) use ($collab) {
            $task->collaborators()->syncWithoutDetaching([
                $collab->id => [
                    'role' => 'editor',
                    'can_edit' => true,
                    'can_delete' => false,
                    'can_invite' => true,
                    'invited_at' => now(),
                    'accepted_at' => now(),
                ],
            ]);
        });
    }
}

