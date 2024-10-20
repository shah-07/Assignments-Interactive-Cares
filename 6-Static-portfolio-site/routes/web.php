<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\WorkExpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/experiences', [WorkExpController::class, 'index']);
Route::get('/projects', [ProjectsController::class, 'index']);

Route::get('/projects/{id}', [ProjectsController::class, 'show'])->name('project.show');
