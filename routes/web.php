<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AnnounceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwoFactorController;
use App\Http\Middleware\Verify2FAMiddleware;
use App\Http\Controllers\ReportController;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// Route::middleware(['auth',Verify2FAMiddleware::class])->group(function(){
//     Route::get('/two-factor', [TwoFactorController::class, 'index'])->name('two-factor.index');
//     Route::post('/two-factor', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
// });

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/evacuation-map', [LandingController::class, 'map'])->name('map');
Route::get('/emergency-contacts', [LandingController::class, 'emergency'])->name('emergency');
Route::get('/emergency', [LandingController::class, 'emergency']);

// Authentication
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Auth)
|--------------------------------------------------------------------------
*/


// Route::middleware(['auth', Verify2FAMiddleware::class])->group(function () {


    /*
    |---------------------------
    | Dashboard
    |---------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    

  Route::get('/report-incident', [AlertController::class, 'create'])->name('report.create');
    Route::post('/report-incident', [AlertController::class, 'store'])->name('reports.store');
 
    // Admin routes (protected by auth middleware)
   Route::middleware(['auth'])->group(function () {
    // Index - list all incidents
    Route::get('/admin/incidents', [AlertController::class, 'index'])->name('incidents.index');
    
    // Statistics - must come before the show route
    Route::get('/admin/incidents/statistics', [AlertController::class, 'getStatistics'])->name('incidents.statistics');
    
    // Export - must come before the show route
    Route::get('/admin/incidents/export', [AlertController::class, 'export'])->name('incidents.export');
    
    // Show - specific incident details
    Route::get('/admin/incidents/{id}', [AlertController::class, 'show'])->name('incidents.show');
    
    // Update status - patch method for updating
    Route::patch('/admin/incidents/{id}/status', [AlertController::class, 'updateStatus'])->name('incidents.update-status');


   Route::get('/announcements', [AnnounceController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [AnnounceController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AnnounceController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [AnnounceController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{announcement}', [AnnounceController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnounceController::class, 'destroy'])->name('announcements.delete');
    
    // Bulk actions
    Route::post('/announcements/bulk-action', [AnnounceController::class, 'bulkAction'])->name('announcements.bulk-action');
    
    // Stats
    Route::get('/announcements/stats', [AnnounceController::class, 'getStats'])->name('announcements.stats');
});
// });
