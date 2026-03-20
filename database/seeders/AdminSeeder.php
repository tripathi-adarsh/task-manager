<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        $admin = User::firstOrCreate(['email' => 'admin@admin.com'], [
            'name'     => 'Admin',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'is_active'=> true,
        ]);

        // Sample users
        $user1 = User::firstOrCreate(['email' => 'john@example.com'], [
            'name'     => 'John Doe',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'is_active'=> true,
        ]);

        $user2 = User::firstOrCreate(['email' => 'jane@example.com'], [
            'name'     => 'Jane Smith',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'is_active'=> true,
        ]);

        // Categories
        $categories = [
            ['name' => 'Development', 'color' => '#0d6efd'],
            ['name' => 'Design',      'color' => '#6f42c1'],
            ['name' => 'Marketing',   'color' => '#fd7e14'],
            ['name' => 'Testing',     'color' => '#20c997'],
            ['name' => 'Management',  'color' => '#dc3545'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }

        $devCat  = Category::where('name', 'Development')->first();
        $desCat  = Category::where('name', 'Design')->first();
        $testCat = Category::where('name', 'Testing')->first();

        // Sample tasks
        $tasks = [
            ['title' => 'Setup project structure',   'description' => 'Initialize Laravel project', 'status' => 'completed', 'priority' => 'high',   'due_date' => now()->subDays(5),  'category_id' => $devCat->id,  'assigned_to' => $user1->id],
            ['title' => 'Design database schema',    'description' => 'ERD and migrations',          'status' => 'completed', 'priority' => 'high',   'due_date' => now()->subDays(3),  'category_id' => $devCat->id,  'assigned_to' => $admin->id],
            ['title' => 'Build admin dashboard UI',  'description' => 'Responsive admin panel',      'status' => 'in_progress','priority' => 'high',  'due_date' => now()->addDays(2),  'category_id' => $desCat->id,  'assigned_to' => $user2->id],
            ['title' => 'Write unit tests',          'description' => 'PHPUnit test coverage',       'status' => 'pending',   'priority' => 'medium', 'due_date' => now()->addDays(7),  'category_id' => $testCat->id, 'assigned_to' => $user1->id],
            ['title' => 'API documentation',         'description' => 'Swagger/OpenAPI docs',        'status' => 'pending',   'priority' => 'low',    'due_date' => now()->addDays(10), 'category_id' => $devCat->id,  'assigned_to' => $user2->id],
            ['title' => 'Fix login bug',             'description' => 'Session not persisting',      'status' => 'in_progress','priority' => 'high',  'due_date' => now()->subDay(),    'category_id' => $devCat->id,  'assigned_to' => $admin->id],
        ];

        foreach ($tasks as $task) {
            Task::create(array_merge($task, ['user_id' => $admin->id]));
        }
    }
}
