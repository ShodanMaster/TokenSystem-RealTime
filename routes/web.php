<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Counter\CounterController as CounterCounterController;
use App\Http\Controllers\Counter\ReportController as CounterReportController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[IndexController::class, 'index']);
Route::get('window-load', [IndexController::class, 'windowLoad'])->name('windowload');
Route::get('login',[LoginController::class, 'index'])->name('login');
Route::post('logging-in', [LoginController::class, 'loggingIn'])->name('loggingin');

Route::middleware('auth:web')->prefix('admin')->name('admin.')->group(function(){
    Route::get('', [AdminController::class, 'index'])->name('index');
    Route::get('issue', [AdminController::class, 'issue'])->name('issue');
    Route::get('window-load', [AdminController::class, 'windowLoad'])->name('windowload');

    Route::post('add-token', [AdminController::class, 'addToken'])->name('addtoken');

    Route::get('counter', [CounterController::class, 'index'])->name('counter');
    Route::get('get-counters', [CounterController::class, 'getCounters'])->name('getcounters');
    Route::get('create-counter', [CounterController::class, 'createCounter'])->name('createcounter');
    Route::post('update-status', [CounterController::class, 'updateStatus'])->name('updatestatus');

    Route::get('token-report', [ReportController::class, 'tokenReport'])->name('tokenreport');
    Route::get('get-token-report', [ReportController::class, 'getTokenReport'])->name('gettokenreport');
    Route::get('export-token-report', [ReportController::class, 'tokenReportExcel'])->name('exporttokenreport');
    Route::get('detailed-token-report/{date}', [ReportController::class, 'detailedTokenReport'])->name('detailedtokenreport');
    Route::get('get-detailed-token-report/{date}', [ReportController::class, 'getDetailedTokenReport'])->name('getdetailedtokenreport');
    Route::get('/export-detailed-token/{date}', [ReportController::class, 'detailedTokenReportExcel'])->name('exportdetailedtoken');
    Route::get('counter-report', [ReportController::class, 'counterReport'])->name('counterreport');
    Route::get('get-counter-report', [ReportController::class, 'getCounterReport'])->name('getcounterreport');
    Route::get('/export-counter-report', [ReportController::class, 'counterReportExcel'])->name('exportcounterreport');
    Route::get('detailed-counter-report/{counter}', [ReportController::class, 'detailedCounterReport'])->name('detailedcounterreport');
    Route::get('/export-detailed-counter/{counter}', [ReportController::class, 'detailedCounterReportExcel'])->name('exportdetailedcounter');
    Route::get('get-detailed-counter-report/{counter}', [ReportController::class, 'getDetailedCounterReport'])->name('getdetailedcounterreport');

    Route::get('logging-out', [LoginController::class, 'loggingOut'])->name('loggingout');

});

Route::middleware(['auth:counter', 'counterclosed'])->prefix('counter')->name('counter.')->group(function(){
    Route::get('', [CounterCounterController::class, 'index'])->name('index');
    Route::get('window-load', [CounterCounterController::class, 'windowLoad'])->name('windowload');

    Route::get('get-token/{id}', [CounterCounterController::class, 'getToken'])->name('gettoken');

    Route::get('report', [CounterReportController::class, 'index'])->name('report');
    Route::get('get-report', [CounterReportController::class, 'getReport'])->name('getreport');
    Route::get('get-report-excel', [CounterReportController::class, 'getReportExcel'])->name('getreportexcel');
    Route::get('detailed-report/{date}', [CounterReportController::class, 'detailedReport'])->name('detailedreport');
    Route::get('get-detailed-report/{date}', [CounterReportController::class, 'getDetailedReport'])->name('getdetailedreport');
    Route::get('get-detailed-report-excel/{date}', [CounterReportController::class, 'getDetailedReportExcel'])->name('getdetailedreportexcel');

    Route::get('logging-out', [LoginController::class, 'loggingOut'])->name('loggingout');
});

Route::get('closed-counter', function(){
    return view('closedcounter');
})->name('closedcounter');
