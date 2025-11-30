<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskAttachmentsSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'demo@example.com')->first();
        if (!$owner) {
            return;
        }

        // Tambahkan attachments dan time tracking pada tugas demo
        Task::where('user_id', $owner->id)->with('category')->get()->each(function (Task $task) {
            $attachments = [];
            $estimated = null;
            $actual = null;

            $cat = optional($task->category)->name;
            switch ($cat) {
                case 'Work':
                    $attachments = ['docs/specification.pdf', 'images/design.png'];
                    $estimated = 120; // 2 jam
                    $actual = 150;    // 2.5 jam
                    break;
                case 'Personal':
                    $attachments = ['notes/fitness-plan.txt'];
                    $estimated = 30;  // 0.5 jam
                    $actual = 35;     // 0.58 jam
                    break;
                case 'Errands':
                    $attachments = ['lists/groceries.csv'];
                    $estimated = 45;  // 0.75 jam
                    $actual = 60;     // 1 jam
                    break;
                default:
                    $attachments = [];
            }

            $task->update([
                'attachments' => $attachments,
                'estimated_minutes' => $estimated,
                'actual_minutes' => $actual,
            ]);
        });
    }
}

