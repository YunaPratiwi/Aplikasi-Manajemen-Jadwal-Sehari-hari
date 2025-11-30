<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskCompletionSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'demo@example.com')->first();
        if (!$owner) {
            return;
        }

        // Tandai 1 tugas dengan status completed (menggunakan method model agar konsisten)
        $task = Task::where('user_id', $owner->id)
            ->orderBy('sort_order')
            ->first();

        if ($task) {
            $task->markAsCompleted();
        }
    }
}

