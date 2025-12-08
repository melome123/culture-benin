<?php

use Illuminate\Support\Facades\Route;

use function Pest\Laravel\get;
use App\Models\Contenu;

Route::get('/dashboard', [App\Http\Controllers\ContenuController::class, 'dashboard'])->name('dashboard');

// Route pour envoyer une note sur un contenu (auth requise)
Route::post('/contenus/{id}/rate', [App\Http\Controllers\ContenuController::class, 'rate'])->middleware(['auth'])->name('contenus.rate');

// Route publique pour poster un commentaire sur un contenu
Route::post('/contenus/{id}/comment', [App\Http\Controllers\CommentaireController::class, 'storeForContenu'])->middleware(['auth'])->name('contenus.comment');

Route::post('demande', [App\Http\Controllers\DemandeController::class, 'store'])->name('demande.store');

