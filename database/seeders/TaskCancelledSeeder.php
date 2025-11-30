<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskCancelledSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'demo@example.com')->first();
        if (!$owner) {
            return;
        }

        // Tandai satu tugas sebagai cancelled (pilih tugas terakhir berdasarkan sort_order)
        $task = Task::where('user_id', $owner->id)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($task) {
            $task->update([
                'status' => Task::STATUS_CANCELLED,
                'is_completed' => false,
                'completed_at' => null,
            ]);
        }
    }
}

