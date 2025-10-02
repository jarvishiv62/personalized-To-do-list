<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DiaryController;
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

// Pomodoro Timer Routes
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