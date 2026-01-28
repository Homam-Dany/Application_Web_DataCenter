<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\IncidentController;

/*
|--- 1. ROUTES PUBLIQUES ---
*/
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/catalogue', [ResourceController::class, 'index'])->name('resources.index');

// La page À Propos centralise désormais tout (Règles + Équipe)
Route::get('/a-propos', function () {
    return view('about');
})->name('about');

/*
|--- 2. ROUTES AUTHENTIFIÉES ---
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');

    // Profil Utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:user'])->group(function () {
        Route::get('/mes-reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reserver', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reserver', [ReservationController::class, 'store'])->name('reservations.store');
        Route::post('/incidents/signaler', [IncidentController::class, 'store'])->name('incidents.store');
    });

    Route::middleware(['role:admin,responsable'])->group(function () {
        Route::get('/gestion/ressources', [ResourceController::class, 'managerIndex'])->name('resources.manager');
        Route::get('/gestion/incidents', [IncidentController::class, 'index'])->name('incidents.manager');
        Route::patch('/incidents/{incident}/resolu', [IncidentController::class, 'resolve'])->name('incidents.resolve');

        Route::get('/gestion/ressources/creer', [ResourceController::class, 'create'])->name('resources.create');
        Route::post('/gestion/ressources', [ResourceController::class, 'store'])->name('resources.store');
        Route::get('/gestion/ressources/{resource}/modifier', [ResourceController::class, 'edit'])->name('resources.edit');
        Route::patch('/gestion/ressources/{resource}', [ResourceController::class, 'update'])->name('resources.update');
        Route::patch('/resources/{resource}/toggle-maintenance', [ResourceController::class, 'toggleMaintenance'])->name('resources.toggleMaintenance');
        Route::post('/reservations/decide/{id}/{action}', [ReservationController::class, 'decide'])->name('reservations.decide');
    });

    Route::middleware(['role:responsable'])->group(function () {
        Route::get('/gestion/demandes', [ReservationController::class, 'managerIndex'])->name('reservations.manager');
        Route::get('/gestion/historique', [ReservationController::class, 'history'])->name('reservations.history');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/utilisateurs', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/logs', [AdminController::class, 'logs'])->name('admin.logs');
        Route::patch('/admin/utilisateurs/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    });

    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});

require __DIR__ . '/auth.php';