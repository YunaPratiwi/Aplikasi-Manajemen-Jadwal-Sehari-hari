<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@example.com')->first();
        if (!$user) {
            return; // DatabaseSeeder should have created the user
        }

        $categories = [
            [
                'name' => 'Personal',
                'description' => 'Tugas personal dan aktivitas sehari-hari',
                'color' => '#3B82F6',
                'icon' => 'user',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Work',
                'description' => 'Tugas pekerjaan dan proyek',
                'color' => '#10B981',
                'icon' => 'briefcase',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Errands',
                'description' => 'Keperluan dan urusan lainnya',
                'color' => '#F59E0B',
                'icon' => 'shopping-cart',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(
                ['user_id' => $user->id, 'name' => $data['name']],
                $data + ['user_id' => $user->id]
            );
        }
    }
}

