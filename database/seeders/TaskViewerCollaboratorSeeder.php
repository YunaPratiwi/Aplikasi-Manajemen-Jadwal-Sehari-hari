<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskViewerCollaboratorSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'demo@example.com')->first();
        $viewer = User::where('email', 'viewer@example.com')->first();

        if (!$owner || !$viewer) {
            return;
        }

        // Tambahkan viewer ke sebagian tugas (misal 1 tugas pertama berdasarkan sort_order)
        Task::where('user_id', $owner->id)
            ->orderBy('sort_order')
            ->take(1)
            ->get()
            ->each(function ($task) use ($viewer) {
                $task->collaborators()->syncWithoutDetaching([
                    $viewer->id => [
                        'role' => 'viewer',
                        'can_edit' => false,
                        'can_delete' => false,
                        'can_invite' => false,
                        'invited_at' => now(),
                        'accepted_at' => now(),
                    ],
                ]);
            });
    }
}

