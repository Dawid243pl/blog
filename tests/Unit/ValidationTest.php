<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase; // <-- Use Laravel's TestCase
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_requires_title_and_content()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post(route('post.store'), [])
             ->assertSessionHasErrors(['title', 'content']);
    }

    public function test_comment_requires_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
             ->post(route('comment.store', ['post' => $post->id]), [])
             ->assertSessionHasErrors(['comment']);
    }
}
