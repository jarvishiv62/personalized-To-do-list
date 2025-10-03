<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProgressController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Authentication Routes (handled by Laravel Breeze)
require __DIR__.'/auth.php';

// Redirect root to dashboard (only for authenticated users)
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');

    // Task resource routes
    Route::resource('tasks', TaskController::class)->except(['index']);
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    
    // Custom route for toggling task completion
    Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle'])
        ->name('tasks.toggle')
        ->middleware('can:update,task');

    // Goal resource routes
    Route::resource('goals', GoalController::class);

    // Pomodoro Timer Routes
    Route::prefix('pomodoro')->name('pomodoro.')->group(function () {
        Route::get('/', [PomodoroController::class, 'index'])->name('index');
        Route::post('/start', [PomodoroController::class, 'startTimer'])->name('start');
        Route::post('/pause', [PomodoroController::class, 'pauseTimer'])->name('pause');
        Route::post('/reset', [PomodoroController::class, 'resetTimer'])->name('reset');
        Route::get('/status', [PomodoroController::class, 'getStatus'])->name('status');
        Route::post('/update-time', [PomodoroController::class, 'updateRemainingTime'])->name('update-time');
    });

    // Diary Routes
    Route::resource('diary', DiaryController::class);

    // Calendar Routes
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('index');
        Route::get('/events', [CalendarController::class, 'events'])->name('events');
    });

    // Progress Routes
    Route::prefix('progress')->name('progress.')->group(function () {
        Route::get('/', [ProgressController::class, 'index'])->name('index');
        Route::get('/data', [ProgressController::class, 'getData'])->name('data');
    });

    // Chat Routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::post('/message', [ChatController::class, 'message'])->name('message');
        Route::post('/clear', [ChatController::class, 'clear'])->name('clear');
        Route::get('/history', [ChatController::class, 'history'])->name('history');
    });

    // Logout route (POST for security)
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});