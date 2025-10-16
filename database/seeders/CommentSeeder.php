<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::factory()
           ->count(10)
           ->create([
               'user_id' => fn () => rand(1, 10),
               'post_id' => fn () => rand(1, 10),
           ]);
    }
}
