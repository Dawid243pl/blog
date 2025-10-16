<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request, Post $post)
    {
        // Anyone can comment, no need to check policy
        try {
            $data = $request->validated();
            $post->comments()->create([
                'user_id' => Auth::user()?->id,
                'comment' => $data['comment'],
            ]);
            return redirect()->back()->with('success', 'Comment added successfully.');
        } catch (\Exception $e) {
            Log::error('Error adding comment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add comment');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (Auth::user()->cannot('delete', $comment)) {
            abort(403);
        }
        try {
            $comment->delete();
            return redirect()->back()->with('success', 'Comment deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting comment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete comment.');
        }
    }
}
