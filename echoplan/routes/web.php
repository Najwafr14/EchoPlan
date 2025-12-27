<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\User\Event\EventController;
use App\Http\Controllers\User\Event\CommitteeController;
use App\Http\Controllers\User\TaskController;
use App\Http\Controllers\User\ToolsController;
use App\Http\Controllers\User\MeetingController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
});

Route::middleware(['auth', 'role:User'])->prefix('user')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('user.dashboard');
    
    Route::resource('/event', EventController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

    Route::get(
            '/event/{event}/committee',
            [CommitteeController::class, 'index']
        )->name('event.committee.index');

        Route::post(
            '/event/{event}/committee',
            [CommitteeController::class, 'store']
        )->name('event.committee.store');

    Route::resource('/task', TaskController::class);

    Route::get('/tools', [ToolsController::class, 'index'])
        ->name('tools.index');

    Route::get('/meeting/index', [MeetingController::class, 'index'])
        ->name('meeting.index');
    
    Route::get('/meeting/create', [MeetingController::class, 'create'])
        ->name('meeting.create');

    Route::post('/meeting/store', [MeetingController::class, 'store'])
        ->name('meeting.store');
        
   Route::get('/meeting/show', [MeetingController::class, 'show'])
        ->name('meeting.show'); 
});



Route::prefix('owner')->middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('owner.dashboard'))->name('owner.dashboard');
});

require __DIR__.'/auth.php';
