<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayrollReportController;
use App\Http\Controllers\PayrollController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/employees', [PayrollController::class, 'getEmployees']);

Route::prefix('payroll')->group(function () {
    Route::get('/departments', [PayrollReportController::class, 'getDepartments']);
    Route::get('/employees', [PayrollReportController::class, 'getEmployees']);
    Route::post('/report', [PayrollReportController::class, 'generatePayrollReport']);
    Route::post('/summary', [PayrollReportController::class, 'getPayrollSummary']);
});
