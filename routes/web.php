<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProgressController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', [TaskController::class, 'dashboard'])->name('home');

// Dashboard route
Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');

// Task resource routes
Route::resource('tasks', TaskController::class);

// Custom route for toggling task completion
Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');

// Goal resource routes
Route::resource('goals', GoalController::class);

// Pomodoro Timer Routes (NEW - Stage 5)
Route::get('/pomodoro', [PomodoroController::class, 'index'])->name('pomodoro.index');
Route::post('/pomodoro/start', [PomodoroController::class, 'startTimer'])->name('pomodoro.start');
Route::post('/pomodoro/pause', [PomodoroController::class, 'pauseTimer'])->name('pomodoro.pause');
Route::post('/pomodoro/reset', [PomodoroController::class, 'resetTimer'])->name('pomodoro.reset');
Route::get('/pomodoro/status', [PomodoroController::class, 'getStatus'])->name('pomodoro.status');
Route::post('/pomodoro/update-time', [PomodoroController::class, 'updateRemainingTime'])->name('pomodoro.update-time');

// Diary Routes (NEW - Stage 6)
Route::resource('diary', DiaryController::class);

// Calendar Routes (NEW - Stage 6)
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

// Progress Routes (NEW - Stage 7)
Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
Route::get('/progress/data', [ProgressController::class, 'getData'])->name('progress.data');

// Chat Routes (Stage 8)
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/message', [ChatController::class, 'message'])->name('chat.message');
Route::post('/chat/clear', [ChatController::class, 'clear'])->name('chat.clear');
Route::get('/chat/history', [ChatController::class, 'history'])->name('chat.history');