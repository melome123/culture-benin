<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
 return view('welcome');});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';

// Moderation routes (accessible to authenticated moderators/admins)
use App\Http\Controllers\CommentaireController;

Route::middleware(['auth', 'role:1,2',])->group(function () {
    Route::get('/moderation/comments', [CommentaireController::class, 'moderation'])->name('moderation');
    Route::get('/moderation/commentaires/data', [CommentaireController::class, 'data1'])->name('moderation.commentaires.data');
    Route::post('/moderation/commentaires/{id}/validate', [CommentaireController::class, 'validateComment'])
        ->whereNumber('id')
        ->name('moderation.commentaires.validate');
    Route::delete('/moderation/commentaires/{id}', [CommentaireController::class, 'destroy'])
        ->whereNumber('id')
        ->name('moderation.commentaires.destroy');
        // Contenus moderation
        Route::get('/moderation/dashboard', function () {
            return view('moderation.dashboard');
        })->name('mod.dashboard');
        Route::get('/moderation/contenus', [App\Http\Controllers\ContenuController::class, 'moderation'])->name('moderation.contenus');
        Route::get('/moderation/contenus/data', [App\Http\Controllers\ContenuController::class, 'dataModeration'])->name('moderation.contenus.data');
        Route::post('/moderation/contenus/{id}/approve', [App\Http\Controllers\ContenuController::class, 'approve'])->whereNumber('id')->name('moderation.contenus.approve');
        Route::post('/moderation/contenus/{id}/reject', [App\Http\Controllers\ContenuController::class, 'reject'])->whereNumber('id')->name('moderation.contenus.reject');
        Route::get('/moderation/contenus/{id}/verdict', [App\Http\Controllers\ContenuController::class, 'verdict'])->whereNumber('id')->name('moderation.contenus.verdict');
});
