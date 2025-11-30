<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use App\Models\Category;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@example.com')->first();
        if (!$user) {
            return; // DatabaseSeeder should have created the user
        }

        $categoryMap = Category::where('user_id', $user->id)
            ->get()
            ->keyBy('name');

        $tasks = [
            [
                'title' => 'Belanja kebutuhan minggu ini',
                'description' => 'Sayur, buah, susu, roti',
                'priority' => Task::PRIORITY_MEDIUM,
                'status' => Task::STATUS_PENDING,
                'is_completed' => false,
                'due_date' => now()->addDays(2),
                'sort_order' => 1,
                'is_archived' => false,
                'category' => 'Errands',
            ],
            [
                'title' => 'Review PR proyek X',
                'description' => 'Cek logic dan test coverage',
                'priority' => Task::PRIORITY_HIGH,
                'status' => Task::STATUS_IN_PROGRESS,
                'is_completed' => false,
                'due_date' => now()->addDay(),
                'sort_order' => 2,
                'is_archived' => false,
                'category' => 'Work',
            ],
            [
                'title' => 'Olahraga sore',
                'description' => 'Jogging 30 menit di taman',
                'priority' => Task::PRIORITY_LOW,
                'status' => Task::STATUS_PENDING,
                'is_completed' => false,
                'due_date' => now()->addDays(1),
                'sort_order' => 3,
                'is_archived' => false,
                'category' => 'Personal',
            ],
        ];

        foreach ($tasks as $data) {
            $category = $categoryMap->get($data['category']);
            if (!$category) {
                continue;
            }

            Task::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'title' => $data['title'],
                ],
                [
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'priority' => $data['priority'],
                    'status' => $data['status'],
                    'is_completed' => $data['is_completed'],
                    'due_date' => $data['due_date'],
                    'sort_order' => $data['sort_order'],
                    'is_archived' => $data['is_archived'],
                ]
            );
        }
    }
}

