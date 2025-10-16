<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private Post $post;
    private User $adminUser;
    private User $user;
    private Comment $comment;
    private Comment $userComment;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);

        $this->owner = User::factory()->create();
        $this->user = User::factory()->create();
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');

        $this->post = Post::factory()->create([
            'user_id' => $this->owner->id,
            'title' => 'Custom Post',
            'content' => 'Custom Content',
        ]);

        $this->comment = Comment::factory()->create([
            'user_id' => $this->owner->id,
            'post_id' => $this->post->id,
            'comment' => 'Custom Comment',
        ]);

        $this->userComment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
            'comment' => 'Other User',
        ]);
    }

    public function test_post_index_screen_is_accessible(): void
    {
        $this->get(route('post.index'))->assertOk();
    }

    public function test_authenticated_user_can_see_post_create_form(): void
    {
        $this->actingAs($this->user)->get(route('post.create'))
         ->assertOk()
         ->assertInertia(
             fn ($page) =>
             $page->component('Posts/Create')
         );
    }

    public function test_unauthenticated_user_can__not_see_post_create_form(): void
    {
        $this->get(route('post.create'))
        ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_view_edit_form(): void
    {
        $this->get(route('post.edit', $this->post))->assertRedirect(route('login'));
    }

    public function test_non_owner_cannot_view_edit_form(): void
    {
        $this->actingAs($this->user)->get(route('post.edit', $this->post))->assertStatus(403);
    }

    public function test_owner_can_view_edit_form(): void
    {
        $this->actingAs($this->owner)->get(route('post.edit', $this->post))->assertOk();
    }

    public function test_authenticated_user_can_create_post(): void
    {
        $this->actingAs($this->owner)->post(route('post.store'), [
        'title' => 'My First Post',
        'content' => 'Test Content',
    ]);

        $this->assertDatabaseHas('posts', [
            'user_id' => $this->owner->id,
            'title'   => 'My First Post',
            'content' => 'Test Content',
        ]);
    }

    public function test_guest_cannot_create_post(): void
    {
        $this->post(route('post.store'), [
            'title' => 'My Error Post',
            'content' => 'Test Error Content',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseMissing('posts', [
            'title' => 'My Error Post',
            'content' => 'Test Error Content',
        ]);
    }

    public function test_owner_can_update_post(): void
    {
        $this->actingAs($this->owner)->put(route('post.update', $this->post->id), [
            'title' => 'Updated Post',
            'content' => 'Updated Content',
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Updated Post',
            'content' => 'Updated Content',
        ]);
    }

    public function test_admin_can_update_post(): void
    {
        $this->actingAs($this->adminUser)->put(route('post.update', $this->post->id), [
            'title' => 'Updated Post By Admin',
            'content' => 'Updated Content By Admin',
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Updated Post By Admin',
            'content' => 'Updated Content By Admin',
        ]);
    }

    public function test_non_owner_non_admin_cannot_update_post(): void
    {
        $this->actingAs($this->user)->put(route('post.update', $this->post->id), [
            'title' => 'Updated Post By Any User',
            'content' => 'Updated Content By Any User',
        ])->assertStatus(403);

        $this->assertDatabaseMissing('posts', [
            'title' => 'Updated Post By Any User',
            'content' => 'Updated Content By Any User',
        ]);
    }

    public function test_any_user_can_view_post_with_comments(): void
    {

        $this->get(route('post.show', $this->post->id))
            ->assertOk()
            ->assertSee('Custom Post')
            ->assertSee('Custom Content')
            ->assertSee('Custom Comment');

        $this->actingAs($this->user)
            ->get(route('post.show', $this->post->id))
            ->assertOk()
            ->assertSee('Custom Post')
            ->assertSee('Custom Content')
            ->assertSee('Custom Comment');
    }

    public function test_owner_can_delete_post(): void
    {
        $this->actingAs($this->owner)->delete(route('post.destroy', $this->post->id))
        ->assertRedirect();

        $this->assertDatabaseMissing('posts', ['id' => $this->post->id]);
    }

    public function test_admin_can_delete_post(): void
    {
        $this->actingAs($this->adminUser)->delete(route('post.destroy', $this->post->id))
        ->assertRedirect();

        $this->assertDatabaseMissing('posts', ['id' => $this->post->id]);
    }

    public function test_non_owner_non_admin_cannot_delete_post(): void
    {
        $this->actingAs($this->user)->delete(route('post.destroy', $this->post->id))
        ->assertStatus(403);

        $this->assertDatabaseHas('posts', ['id' => $this->post->id]);
    }

    public function test_guest_cannot_delete_post(): void
    {
        $this->delete(route('post.destroy', $this->post->id))
        ->assertRedirect(route('login'));

        $this->assertDatabaseHas('posts', ['id' => $this->post->id]);
    }

    public function test_guest_can_add_comment(): void
    {
        $this->post(route('comment.store', $this->post), ['comment' => 'Hello'])->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'post_id' => $this->post->id,
            'comment' => 'Hello',
            'user_id' => null,
        ]);
    }

    public function test_user_can_add_comment(): void
    {
        $this->actingAs($this->owner)
             ->post(route('comment.store', $this->post), ['comment' => 'Hi'])
             ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'post_id' => $this->post->id,
            'comment' => 'Hi',
            'user_id' => $this->owner->id,
        ]);
    }

    public function test_comment_owner_can_delete_own_comment(): void
    {
        $this->actingAs($this->owner)->delete(route('comment.destroy', $this->comment))->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $this->comment->id]);
    }

    public function test_post_owner_can_delete_any_comment_on_their_post(): void
    {
        $this->actingAs($this->owner)->delete(route('comment.destroy', $this->userComment->id))->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $this->userComment->id]);
    }

    public function test_admin_can_delete_any_comment(): void
    {
        $post = Post::factory()->for(User::factory())->create();
        $comment = Comment::factory()->for($post)->for(User::factory())->create();

        $this->actingAs($this->adminUser)->delete(route('comment.destroy', $comment))->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_random_user_cannot_delete_others_comment(): void
    {
        $this->actingAs($this->user)->delete(route('comment.destroy', $this->comment->id))->assertStatus(403);
        $this->assertDatabaseHas('comments', ['id' => $this->comment->id]);
    }

    public function test_guest_cannot_delete_comment(): void
    {
        $this->delete(route('comment.destroy', $this->comment->id))->assertRedirect(route('login'));
        $this->assertDatabaseHas('comments', ['id' => $this->comment->id]);
    }
}
