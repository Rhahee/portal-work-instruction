<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ManageWorkInstructionController;
use App\Http\Controllers\UserController;
use App\Models\WorkInstruction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('home', ['recentInstructions' => WorkInstruction::with('category')->published()->visibleTo(request()->user())->latest('published_at')->take(4)->get()]); })->name('home');
Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
Route::get('/wi/{workInstruction:slug}', [LibraryController::class, 'show'])->name('library.show');
Route::get('/wi/{workInstruction:slug}/attachment', [LibraryController::class, 'attachment'])->name('library.attachment');

Route::middleware('guest')->group(function () { Route::get('/login', [AuthController::class, 'create'])->name('login'); Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store'); });
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');
Route::middleware(['auth', 'role:it,admin'])->prefix('dashboard')->name('dashboard.')->group(function () { Route::get('/', DashboardController::class)->name('index'); });
Route::middleware(['auth', 'role:it,admin'])->prefix('manage')->name('manage.')->group(function () { Route::resource('instructions', ManageWorkInstructionController::class)->except('show'); Route::post('instructions/{workInstruction}/review', [ManageWorkInstructionController::class, 'review'])->name('instructions.review'); Route::post('instructions/{instruction}/deletion-review', [ManageWorkInstructionController::class, 'reviewDeletion'])->name('instructions.deletion-review'); Route::post('uploads/images', [ManageWorkInstructionController::class, 'upload'])->name('uploads.images'); });
Route::middleware(['auth', 'role:admin'])->prefix('manage')->name('manage.')->group(function () { Route::resource('categories', CategoryController::class)->only(['index', 'store', 'edit', 'update', 'destroy']); Route::resource('users', UserController::class)->only(['index', 'store', 'edit', 'update', 'destroy']); });
