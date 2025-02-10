<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Halo\HaloController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::get('/halo', [HaloController::class, 'index']);


Route::middleware('auth')->group(function () {
    
    Route::get('/', function () {
        return view('welcome');
    });

    
    Route::get('/tasks', [TaskController::class, 'index']) ->name('tasks.index');

    
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [TaskController::class, 'editTask'])->name('tasks.edit');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.delete');
    Route::put('/tasks/{task}/toggle-done', [TaskController::class, 'toggleDone'])->name('tasks.toggle-done');

    
    Route::post('/tasks/{task}/subtasks', [TaskController::class, 'subtaskStore'])->name('subtasks.store');
    Route::put('/subtask/{id}', [TaskController::class, 'editSubtask'])->name('subtasks.edit');
    Route::put('/subtasks/{subtask}/toggle-done', [TaskController::class, 'subtaskToggleDone'])->name('subtasks.toggle-done');
    Route::delete('/subtasks/{subtask}', [TaskController::class, 'deleteSubtask'])->name('subtasks.delete');


    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    
    Route::get('/profile/setting', [ProfileController::class, 'setting'])->name('account.setting');
    Route::put('/profile/setting/update', [ProfileController::class, 'update'])->name('account.update');
    
    
    Route::get('/profile/leaderboard', [ProfileController::class, 'leaderboard'])->name('profile.leaderboard');

});


Route::middleware('guest')->group(function () {
    Route::get('/registerasi', [AuthController::class, 'tampilRegisterasi'])->name('registerasi.tampil');
    Route::post('/registerasi/submit', [AuthController::class, 'submitRegisterasi'])->name('registerasi.submit');
    
    Route::get('/login', [AuthController::class, 'tampilLogin'])->name('login');
    Route::post('/login/submit', [AuthController::class, 'submitLogin'])->name('login.submit');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


