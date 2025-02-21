<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Counter\CounterController as CounterCounterController;
use App\Http\Controllers\Counter\ReportController as CounterReportController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('login',[LoginController::class, 'index'])->name('login');
Route::post('logging-in', [LoginController::class, 'loggingIn'])->name('loggingin');

Route::middleware('auth:web')->prefix('admin')->name('admin.')->group(function(){
    Route::get('', [AdminController::class, 'index'])->name('index');
    Route::get('window-load', [AdminController::class, 'windowLoad'])->name('windowload');

    Route::post('add-token', [AdminController::class, 'addToken'])->name('addtoken');

    Route::get('counter', [CounterController::class, 'index'])->name('counter');
    Route::get('get-counters', [CounterController::class, 'getCounters'])->name('getcounters');
    Route::get('create-counter', [CounterController::class, 'createCounter'])->name('createcounter');
    Route::post('update-status', [CounterController::class, 'updateStatus'])->name('updatestatus');

    Route::get('token-report', [ReportController::class, 'tokenReport'])->name('tokenreport');
    Route::get('get-token-report', [ReportController::class, 'getTokenReport'])->name('gettokenreport');
    Route::get('detailed-token-report/{date}', [ReportController::class, 'detailedTokenReport'])->name('detailedtokenreport');
    Route::get('get-detailed-token-report/{date}', [ReportController::class, 'getDetailedTokenReport'])->name('getdetailedtokenreport');
    Route::get('counter-report', [ReportController::class, 'counterReport'])->name('counterreport');
    Route::get('detailed-counter-report/{counter}', [ReportController::class, 'detailedCounterReport'])->name('detailedcounterreport');

    Route::get('logging-out', [LoginController::class, 'loggingOut'])->name('loggingout');

});

Route::middleware(['auth:counter', 'counterclosed'])->prefix('counter')->name('counter.')->group(function(){
    Route::get('', [CounterCounterController::class, 'index'])->name('index');
    Route::get('window-load', [CounterCounterController::class, 'windowLoad'])->name('windowload');

    Route::get('get-token/{id}', [CounterCounterController::class, 'getToken'])->name('gettoken');

    Route::get('report', [CounterReportController::class, 'index'])->name('report');
    Route::get('detailed-report/{date}', [CounterReportController::class, 'detailedReport'])->name('detailedreport');

    Route::get('logging-out', [LoginController::class, 'loggingOut'])->name('loggingout');
});

Route::get('closed-counter', function(){
    return view('closedcounter');
})->name('closedcounter');
