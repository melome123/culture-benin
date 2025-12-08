<?php

use App\Http\Controllers\LangueController;
use App\Http\Controllers\ContenuController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ParlerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TypecontenuController;
use App\Http\Controllers\TypemediaController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\DashController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth','role:1'])->group(function () {

    // Admin routes grouped with prefix and name 'admin.'
Route::prefix('admin')->name('admin.')->group(function () {
    // NOTE: explicit list/data routes defined below. Avoid Route::resource here because
    // resource 'show' routes like /admin/utilisateurs/{utilisateur} will match 'list' as {utilisateur}.
    // If you want resource routes, define them after the explicit static routes or add numeric constraints.
    
    // Admin dashboard
    Route::get('dashboard', [DashController::class,'index'])->name('dashboard');
    
    Route::get('/', function () {
        return view('auth');
    });

Route::get('/langues/list', function () {
    return view('langues');
})->name('langues.public');
    // List pages (DataTable views) - named as admin.{resource}
    Route::get('langues/list', [LangueController::class,'list'])->name('langues');
    Route::get('contenus/list', [ContenuController::class,'list'])->name('contenus');
    Route::get('regions/list', [RegionController::class,'list'])->name('regions');
    Route::get('users/list', [UserController::class,'list'])->name('users');
    Route::get('commentaires/list', [CommentaireController::class,'list'])->name('commentaires');
    Route::get('medias/list', [MediaController::class,'list'])->name('medias');
    Route::get('parlers/list', [ParlerController::class,'list'])->name('parlers');
    Route::get('roles/list', [RoleController::class,'list'])->name('roles');
    Route::get('typecontenus/list', [TypecontenuController::class,'list'])->name('typecontenus');
    Route::get('typemedias/list', [TypemediaController::class,'list'])->name('typemedias');

    // Data endpoints for DataTables - named admin.{resource}.data
    Route::get('langues/data', [LangueController::class,'data'])->name('langues.data');
    Route::get('contenus/data', [ContenuController::class,'data'])->name('contenus.data');
    Route::get('regions/data', [RegionController::class,'data'])->name('regions.data');
    Route::get('users/data', [UserController::class,'data'])->name('users.data');
    Route::get('commentaires/data', [CommentaireController::class,'data'])->name('commentaires.data');
    Route::get('medias/data', [MediaController::class,'data'])->name('medias.data');
    Route::get('parlers/data', [ParlerController::class,'data'])->name('parlers.data');
    Route::get('roles/data', [RoleController::class,'data'])->name('roles.data');
    Route::get('typecontenus/data', [TypecontenuController::class,'data'])->name('typecontenus.data');
    Route::get('typemedias/data', [TypemediaController::class,'data'])->name('typemedias.data');

    // CRUD routes (explicit) - keep explicit definitions for clarity and to avoid parameter conflicts
    // Langues
    Route::get('langues/create', [LangueController::class,'create'])->name('langues.create');
    Route::post('langues', [LangueController::class,'store'])->name('langues.store');
    Route::get('langues/{id}', [LangueController::class,'show'])->whereNumber('id')->name('langues.show');
    Route::get('langues/{id}/edit', [LangueController::class,'edit'])->whereNumber('id')->name('langues.edit');
    Route::put('langues/{id}', [LangueController::class,'update'])->whereNumber('id')->name('langues.update');
    Route::delete('langues/{id}', [LangueController::class,'destroy'])->whereNumber('id')->name('langues.destroy');

    // Contenus
    Route::get('contenus/create', [ContenuController::class,'create'])->name('contenus.create');
    Route::post('contenus', [ContenuController::class,'store'])->name('contenus.store');
    Route::get('contenus/{id}', [ContenuController::class,'show1'])->whereNumber('id')->name('contenus.show');
    Route::get('contenus/{id}/edit', [ContenuController::class,'edit'])->whereNumber('id')->name('contenus.edit');
    Route::put('contenus/{id}', [ContenuController::class,'update'])->whereNumber('id')->name('contenus.update');
    Route::delete('contenus/{id}', [ContenuController::class,'destroy'])->whereNumber('id')->name('contenus.destroy');

    // Commentaires
    Route::get('commentaires/create', [CommentaireController::class,'create'])->name('commentaires.create');
    Route::post('commentaires', [CommentaireController::class,'store'])->name('commentaires.store');
    Route::get('commentaires/{id}', [CommentaireController::class,'show'])->whereNumber('id')->name('commentaires.show');
    Route::get('commentaires/{id}/edit', [CommentaireController::class,'edit'])->whereNumber('id')->name('commentaires.edit');
    Route::put('commentaires/{id}', [CommentaireController::class,'update'])->whereNumber('id')->name('commentaires.update');
    Route::delete('commentaires/{id}', [CommentaireController::class,'destroy'])->whereNumber('id')->name('commentaires.destroy');

    // Medias
    Route::get('medias/create', [MediaController::class,'create'])->name('medias.create');
    Route::post('medias', [MediaController::class,'store'])->name('medias.store');
    Route::get('medias/{id}', [MediaController::class,'show'])->whereNumber('id')->name('medias.show');
    Route::get('medias/{id}/edit', [MediaController::class,'edit'])->whereNumber('id')->name('medias.edit');
    Route::put('medias/{id}', [MediaController::class,'update'])->whereNumber('id')->name('medias.update');
    Route::delete('medias/{id}', [MediaController::class,'destroy'])->whereNumber('id')->name('medias.destroy');

    // Regions
    Route::get('regions/create', [RegionController::class,'create'])->name('regions.create');
    Route::post('regions', [RegionController::class,'store'])->name('regions.store');
    Route::get('regions/{id}', [RegionController::class,'show'])->whereNumber('id')->name('regions.show');
    Route::get('regions/{id}/edit', [RegionController::class,'edit'])->whereNumber('id')->name('regions.edit');
    Route::put('regions/{id}', [RegionController::class,'update'])->whereNumber('id')->name('regions.update');
    Route::delete('regions/{id}', [RegionController::class,'destroy'])->whereNumber('id')->name('regions.destroy');

    // Roles
    Route::get('roles/create', [RoleController::class,'create'])->name('roles.create');
    Route::post('roles', [RoleController::class,'store'])->name('roles.store');
    Route::get('roles/{id}', [RoleController::class,'show'])->whereNumber('id')->name('roles.show');
    Route::get('roles/{id}/edit', [RoleController::class,'edit'])->whereNumber('id')->name('roles.edit');
    Route::put('roles/{id}', [RoleController::class,'update'])->whereNumber('id')->name('roles.update');
    Route::delete('roles/{id}', [RoleController::class,'destroy'])->whereNumber('id')->name('roles.destroy');

    // Typecontenus
    Route::get('typecontenus/create', [TypecontenuController::class,'create'])->name('typecontenus.create');
    Route::post('typecontenus', [TypecontenuController::class,'store'])->name('typecontenus.store');
    Route::get('typecontenus/{id}', [TypecontenuController::class,'show'])->whereNumber('id')->name('typecontenus.show');
    Route::get('typecontenus/{id}/edit', [TypecontenuController::class,'edit'])->whereNumber('id')->name('typecontenus.edit');
    Route::put('typecontenus/{id}', [TypecontenuController::class,'update'])->whereNumber('id')->name('typecontenus.update');
    Route::delete('typecontenus/{id}', [TypecontenuController::class,'destroy'])->whereNumber('id')->name('typecontenus.destroy');

    // Typemedias
    Route::get('typemedias/create', [TypemediaController::class,'create'])->name('typemedias.create');
    Route::post('typemedias', [TypemediaController::class,'store'])->name('typemedias.store');
    Route::get('typemedias/{id}', [TypemediaController::class,'show'])->whereNumber('id')->name('typemedias.show');
    Route::get('typemedias/{id}/edit', [TypemediaController::class,'edit'])->whereNumber('id')->name('typemedias.edit');
    Route::put('typemedias/{id}', [TypemediaController::class,'update'])->whereNumber('id')->name('typemedias.update');
    Route::delete('typemedias/{id}', [TypemediaController::class,'destroy'])->whereNumber('id')->name('typemedias.destroy');

    // Demandes (registration requests)
    Route::get('demandes/data', [DemandeController::class,'data'])->name('demandes.data');
    Route::post('demandes/{id}/approve', [DemandeController::class,'approve'])->name('demandes.approve');
    Route::post('demandes/{id}/reject', [DemandeController::class,'reject'])->name('demandes.reject');

    // Utilisateurs
    Route::get('users/create', [UserController::class,'create'])->name('users.create');
    Route::get('users/login', [UserController::class,'login'])->name('users.login');
    Route::post('users', [UserController::class,'store'])->name('users.store');
    Route::get('users/{id}', [UserController::class,'show'])->whereNumber('id')->name('users.show');
    Route::get('users/{id}/edit', [UserController::class,'edit'])->whereNumber('id')->name('users.edit');
    Route::put('/admin/users/{u}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UserController::class,'destroy'])->whereNumber('id')->name('users.destroy');

    // Parlers (composite key) keep explicit show/edit/update/delete routes
    Route::get('parlers/{langue_id}/{region_id}', [ParlerController::class,'show'])->whereNumber('langue_id')->whereNumber('region_id')->name('parlers.show');
    Route::get('parlers/{langue_id}/{region_id}/edit', [ParlerController::class,'edit'])->whereNumber('langue_id')->whereNumber('region_id')->name('parlers.edit');
    Route::put('parlers/{langue_id}/{region_id}', [ParlerController::class,'update'])->whereNumber('langue_id')->whereNumber('region_id')->name('parlers.update');
    Route::delete('parlers/{langue_id}/{region_id}', [ParlerController::class,'destroy'])->whereNumber('langue_id')->whereNumber('region_id')->name('parlers.destroy');
});

});
// Legacy / convenience public routes (if you need non-admin access to specific pages, keep here)
// Example: public listing of langues