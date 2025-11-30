<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskEnhancementSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'demo@example.com')->first();
        if (!$owner) {
            return;
        }

        Task::where('user_id', $owner->id)->with('category')->get()->each(function (Task $task) {
            $tags = [];
            $reminder = null;

            $categoryName = optional($task->category)->name;
            switch ($categoryName) {
                case 'Work':
                    $tags = ['work', 'review'];
                    break;
                case 'Personal':
                    $tags = ['fitness', 'health'];
                    break;
                case 'Errands':
                    $tags = ['shopping', 'groceries'];
                    break;
                default:
                    $tags = ['tasks'];
            }

            if ($task->due_date) {
                $reminder = $task->due_date->copy()->subHours(2);
            }

            $task->update([
                'tags' => $tags,
                'reminder_at' => $reminder,
            ]);
        });
    }
}

