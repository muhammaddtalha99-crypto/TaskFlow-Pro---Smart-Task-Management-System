<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) return;

        $categories = [
            ['name' => 'Workout',           'color' => '#EF4444'],
            ['name' => 'Diet Plan',         'color' => '#10B981'],
            ['name' => 'Medical',           'color' => '#3B82F6'],
            ['name' => 'Hydration',         'color' => '#06B6D4'],
            ['name' => 'Daily Exercise',    'color' => '#F59E0B'],
            ['name' => 'Mental Health',     'color' => '#8B5CF6'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'user_id' => $user->id,
                'name'    => $cat['name'],
                'color'   => $cat['color'],
            ]);
        }
    }
}