<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Todo\TodoController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('todos')->middleware('auth:sanctum')->group(function () {
    Route::post('/all', [TodoController::class, 'all'])->name('todos.all');
    Route::post('/', [TodoController::class, 'store'])->name('todos.store');
    Route::get('/{resource}', [TodoController::class, 'show'])->name('todos.show');
    Route::put('/{resource}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/{resource}', [TodoController::class, 'destroy'])->name('todos.destroy');
});
