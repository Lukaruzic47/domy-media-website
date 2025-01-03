<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MediaController;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

    /**
     * Project routes
     */

Route::get('/projects', [ProjectController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('projects');

Route::get('/projects/create', [ProjectController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('projects.create');

Route::post('/projects', [ProjectController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('projects.store');

Route::get('/projects/{slug}/edit', [ProjectController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('projects.edit');

Route::put('/projects/{project:slug}', [ProjectController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('projects.update');

Route::post('/projects/{project:slug}', [ProjectController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('projects.update');

/**
 * Media routes.
 */

Route::get('/media', [MediaController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('media');

Route::prefix('projects')->group(function () {
    Route::get('{project}/get-images', [ProjectImageController::class, 'getImages'])
        ->middleware(['auth', 'verified'])
        ->name('projects.images.index');
    Route::post('{project}/upload-images', [ProjectImageController::class, 'upload'])
        ->middleware(['auth', 'verified'])
        ->name('projects.images.upload');
    Route::delete('images/{image}', [ProjectImageController::class, 'destroy'])
        ->middleware(['auth', 'verified'])
        ->name('projects.images.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // image routes
    Route::delete('/api/images/{id}', [MediaController::class, 'destroy']);
    Route::post('/api/images/', [MediaController::class, 'upload']);
    Route::get('/api/images/{project_id}', [MediaController::class, 'index']);
});

require __DIR__.'/auth.php';
