<?php

use App\Http\Controllers\CrisisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MeditationController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\TherapistController;
use App\Http\Middleware\EnsureWellnessUser;
use Illuminate\Support\Facades\Route;

Route::get('/', [SetupController::class, 'index'])->name('setup');
Route::post('/setup', [SetupController::class, 'store'])->name('setup.store');

Route::post('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::middleware(EnsureWellnessUser::class)->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/mood', [MoodController::class, 'index'])->name('mood.index');
    Route::post('/mood', [MoodController::class, 'store'])->name('mood.store');

    Route::get('/journal', [JournalController::class, 'index'])->name('journal.index');
    Route::post('/journal', [JournalController::class, 'store'])->name('journal.store');

    Route::get('/therapists', [TherapistController::class, 'index'])->name('therapists.index');
    Route::post('/therapists/book', [TherapistController::class, 'book'])->name('therapists.book');

    Route::get('/meditation', [MeditationController::class, 'index'])->name('meditation.index');
    Route::post('/meditation/{slug}', [MeditationController::class, 'startExercise'])->name('meditation.start');

    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/groups/{group}/toggle', [GroupController::class, 'toggle'])->name('groups.toggle');

    Route::get('/crisis', [CrisisController::class, 'index'])->name('crisis.index');
});
