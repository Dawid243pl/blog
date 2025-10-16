<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    /* Unit tests are ideal for testing separate functions, in this task there was not enough logic to justify spreading the code into services
      and having own functions.Therefore I have written an example Unit test but it would be much better to test a single function. */

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
