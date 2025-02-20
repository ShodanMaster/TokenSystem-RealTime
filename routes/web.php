<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Counter\CounterController as CounterCounterController;
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

    Route::post('add-token', [AdminController::class, 'addToken'])->name('addtoken');

    Route::get('counter', [CounterController::class, 'index'])->name('counter');
    Route::get('create-counter', [CounterController::class, 'createCounter'])->name('createcounter');
    Route::post('update-status', [CounterController::class, 'updateStatus'])->name('updatestatus');

    Route::get('report', [ReportController::class, 'index'])->name('report');
    Route::get('detailed-report/{id}', [ReportController::class, 'detailedReport'])->name('detailedreport');

    Route::get('logging-out', [LoginController::class, 'loggingOut'])->name('loggingout');

});

Route::middleware(['auth:counter', 'counterclosed'])->prefix('counter')->name('counter.')->group(function(){
    Route::get('', [CounterCounterController::class, 'index'])->name('index');

    Route::get('get-token/{id}', [CounterCounterController::class, 'getToken'])->name('gettoken');

    Route::get('logging-out', [LoginController::class, 'loggingOut'])->name('loggingout');
});

Route::get('closed-counter', function(){
    return view('closedcounter');
})->name('closedcounter');
