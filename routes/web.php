<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\Event\EventController;
use App\Http\Controllers\User\Event\CommitteeController;
use App\Http\Controllers\User\Event\TimelineController;
use App\Http\Controllers\User\Event\TaskController as EventTaskController;
use App\Http\Controllers\User\Event\BudgetController;
use App\Http\Controllers\User\Event\TalentController;
use App\Http\Controllers\User\Event\VenueController;
use App\Http\Controllers\User\Event\VendorController;
use App\Http\Controllers\User\Event\SponsorController;
use App\Http\Controllers\User\Event\BarangController;
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

    Route::get('/dashboard', [UserDashboard::class, 'index'])
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

    Route::get('/event/{event}/timeline', [TimelineController::class, 'index'])
     ->name('event.timeline.index');

    Route::resource('/task', TaskController::class);
    
    Route::get('/event/{event}/task', [EventTaskController::class, 'index'])
        ->name('user.event.task.index');
    Route::post('/event/{event}/task', [EventTaskController::class, 'store'])
        ->name('user.event.task.store');
    Route::get('/event/task/{task}',[EventTaskController::class, 'show'])
        ->name('user.event.task.show');
    Route::post('/event/task/{task}/progress',[EventTaskController::class, 'storeProgress'])
        ->name('user.event.task.progress.store');

    Route::prefix('user/event/{event}')->group(function () {
        Route::get('/budget', [BudgetController::class, 'index'])->name('user.event.budget.index');
        Route::post('/budget', [BudgetController::class, 'store'])->name('user.event.budget.store');
        Route::put('/budget/{budget}', [BudgetController::class, 'update'])->name('user.event.budget.update');
    });

    Route::get('/event/{event}/talent', [TalentController::class, 'index'])
        ->name('event.talent.index');
    Route::post('/event/{event}/talent', [TalentController::class, 'store'])
        ->name('event.talent.store');
    Route::put('/event/{event}/talent/{talent}', [TalentController::class, 'update'])
        ->name('event.talent.update');
    Route::delete('/event/{event}/talent/{talent}', [TalentController::class, 'destroy'])
        ->name('event.talent.destroy');

    Route::get('/event/{event}/venue', [VenueController::class, 'index'])
    ->name('event.venue.index');
    Route::post('/event/{event}/venue', [VenueController::class, 'store'])
        ->name('event.venue.store');
    Route::put('/event/{event}/venue/{venue}', [VenueController::class, 'update'])
        ->name('event.venue.update');
    Route::delete('/event/{event}/venue/{venue}', [VenueController::class, 'destroy'])
        ->name('event.venue.destroy');

    Route::get('/event/{event}/vendor', [VendorController::class, 'index'])
        ->name('event.vendor.index');
    Route::post('/event/{event}/vendor', [VendorController::class, 'store'])
        ->name('event.vendor.store');
    Route::put('/event/{event}/vendor/{vendor}', [VendorController::class, 'update'])
        ->name('event.vendor.update');
    Route::delete('/event/{event}/vendor/{vendor}', [VendorController::class, 'destroy'])
        ->name('event.vendor.destroy');

    Route::get('/event/{event}/sponsor', [SponsorController::class, 'index'])
        ->name('event.sponsor.index');
    Route::post('/event/{event}/sponsor', [SponsorController::class, 'store'])
        ->name('event.sponsor.store');
    Route::put('/event/{event}/sponsor/{sponsor}', [SponsorController::class, 'update'])
        ->name('event.sponsor.update');
    Route::delete('/event/{event}/sponsor/{sponsor}', [SponsorController::class, 'destroy'])
        ->name('event.sponsor.destroy');

    Route::get('/event/{event}/barang', [BarangController::class, 'index'])
        ->name('event.barang.index');
    Route::post('/event/{event}/barang', [BarangController::class, 'store'])
        ->name('event.barang.store');
    Route::put('/event/{event}/barang/{item}', [BarangController::class, 'update'])
        ->name('event.barang.update');
    Route::delete('/event/{event}/barang/{item}', [BarangController::class, 'destroy'])
        ->name('event.barang.destroy');

    Route::get('/tools', [ToolsController::class, 'index'])
        ->name('tools.index');

    Route::get('/meetings', [MeetingController::class, 'index'])
        ->name('meetings.index');
    Route::post('/meetings', [MeetingController::class, 'store'])
        ->name('meetings.store');
    Route::put('/meetings/{meeting}', [MeetingController::class, 'update'])
        ->name('meetings.update');
    Route::put('/meetings/{meeting}/notes', [MeetingController::class, 'updateNotes'])
        ->name('meetings.updateNotes');
    Route::delete('/meetings/{meeting}', [MeetingController::class, 'destroy'])
        ->name('meetings.destroy');
});



Route::prefix('owner')->middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('owner.dashboard'))->name('owner.dashboard');
});

require __DIR__.'/auth.php';
