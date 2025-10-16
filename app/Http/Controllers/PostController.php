<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('comments.user:id,name', 'user:id,name')->orderBy('created_at', 'desc')->paginate(4);
        return Inertia::render('Posts/Index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Post::class)) {
            abort(403);
        }
        return Inertia::render('Posts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        if (Auth::user()->cannot('create', Post::class)) {
            abort(403);
        }
        $data = $request->validated();
        try {
            $data['user_id'] = Auth::user()?->id;
            Post::create($data);
            return redirect()->back()->with('success', 'Post created successfully.');
        } catch (\Exception $e) {
            Log::error('Error while creating post: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create post.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Any user can view a post
        return Inertia::render('Posts/Show', ['post' => $post->load('comments.user:id,name', 'user:id,name')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if (Auth::user()->cannot('update', $post)) {
            abort(403);
        }
        return Inertia::render('Posts/Edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $post)
    {
        if (Auth::user()->cannot('update', $post)) {
            abort(403);
        }
        $data = $request->validated();

        try {
            $post->update([
                'title' => $data['title'],
                'content' => $data['content']
            ]);
            return redirect()->back()->with('success', 'Post updated successfully.');
        } catch (\Exception $e) {
            Log::error('Validation error while updating post: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update post.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (Auth::user()->cannot('delete', $post)) {
            abort(403);
        }
        try {
            $post->delete();
            return redirect()->back()->with('success', 'Post deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting post: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete post.');
        }
    }
}
