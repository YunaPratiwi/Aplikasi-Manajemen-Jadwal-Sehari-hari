<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskViewerMoreCollaboratorSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'demo@example.com')->first();
        $viewer = User::where('email', 'viewer@example.com')->first();

        if (!$owner || !$viewer) {
            return;
        }

        // Tambahkan viewer pada 2 tugas berikutnya (skip 1 tugas pertama yang sudah ditandai viewer)
        Task::where('user_id', $owner->id)
            ->orderBy('sort_order')
            ->skip(1)
            ->take(2)
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

