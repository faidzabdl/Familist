<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Halo\HaloController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/halo', [HaloController::class, 'index']);

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/tasks');
    } else {
        return view('login'); 
    }
});

Route::middleware('auth')->group(function () {

    // Route::get('/', function () {
    //     return redirect('/tasks'); 
    //     });


    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');


    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [TaskController::class, 'editTask'])->name('tasks.edit');
    Route::delete('/tasks/deleteAll', [TaskController::class, 'deleteAll'])->name('tasks.deleteAll');
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

    Route::post('/tasks/update-reminder-status', [TaskController::class, 'updateReminderStatus'])->name('tasks.updateReminderStatus');

});


Route::middleware('guest')->group(function () {
    // Route::get('/', function () {
    //     return redirect('/login'); 
    //     });

    Route::get('/registerasi', [AuthController::class, 'tampilRegisterasi'])->name('registerasi.tampil');
    Route::post('/registerasi/submit', [AuthController::class, 'submitRegisterasi'])->name('registerasi.submit');

    Route::get('/login', [AuthController::class, 'tampilLogin'])->name('login');
    Route::post('/login/submit', [AuthController::class, 'submitLogin'])->name('login.submit');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}/{email}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->name('password.update');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
