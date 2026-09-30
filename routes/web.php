<?php
use App\Http\Controllers\{AuthController, FocusController, SubtaskController, TaskController};
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');
    Route::view('/settings', 'settings')->name('settings');
    Route::resource('tasks', TaskController::class)->except(['edit']);
    Route::post('/tasks/{task}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
    Route::post('/tasks/{task}/subtasks/reorder', [SubtaskController::class, 'reorder'])->name('subtasks.reorder');
    Route::patch('/subtasks/{subtask}', [SubtaskController::class, 'update'])->name('subtasks.update');
    Route::patch('/subtasks/{subtask}/toggle', [SubtaskController::class, 'toggle'])->name('subtasks.toggle');
    Route::delete('/subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');
    Route::get('/daily-focus', [FocusController::class, 'index'])->name('focus');
    Route::post('/daily-focus', [FocusController::class, 'save'])->name('focus.save');
});
