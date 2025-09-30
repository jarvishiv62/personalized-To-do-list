<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\GoalController;
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