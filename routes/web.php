<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\UserAuthenticated;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Normally the home page would be here.
Route::get('/', fn () => redirect()->route('post.index'));
Route::get('/posts', [PostController::class, 'index'])->name('post.index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(UserAuthenticated::class)->name('dashboard');

// We can use the default 'auth' middleware but I have just made one myself to meet the criteria.
Route::middleware(UserAuthenticated::class)->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('posts')->group(function () {
        Route::get('/create', [PostController::class, 'create'])->name('post.create');
        Route::post('/', [PostController::class, 'store'])->name('post.store');

        Route::get('/{post:id}/edit', [PostController::class, 'edit'])->name('post.edit');
        Route::put('/{post:id}', [PostController::class, 'update'])->name('post.update');
        Route::delete('/{post:id}', [PostController::class, 'destroy'])->name('post.destroy');
    });


    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
});

Route::prefix('posts')->group(function () {
    Route::post('/{post:id}/comments', [CommentController::class, 'store'])->name('comment.store');
    Route::get('/{post:id}', [PostController::class, 'show'])->name('post.show');
});

require __DIR__.'/auth.php';
