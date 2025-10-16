<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $AdminUsers = [
            [
                'name' => 'Dawid Koleczko',
                'email' => 'david.koleczko.cobc@gmail.com',
                'password' => 'password'
            ],
            [
                'name' => 'Petar',
                'email' => 'recruitment@ominimo.eu',
                'password' => 'password',
            ]
        ];

        $users = [
            [
                'name' => 'test user',
                'email' => 'testuser@gmail.com',
                'password' => 'password',
            ]
        ];

        foreach ($AdminUsers as $user) {
            User::create($user)->assignRole('admin');
            $this->command->info('User: '.$user['name'].' created');
        }

        foreach ($users as $user) {
            User::create($user);
            $this->command->info('User: '.$user['name'].' created');
        }

        User::factory()
            ->count(10)
            ->has(
                Post::factory()
                    ->count(4)
                    ->has(
                        Comment::factory()
                            ->count(10)
                            ->for(User::factory())
                    )
            )
            ->create();

    }
}
