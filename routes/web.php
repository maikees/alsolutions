<?php

use App\Http\Controllers\DocumentImportController;
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

Route::get('documents/import', [DocumentImportController::class, 'showImportForm'])->name('documents.import.form');
Route::post('documents/import', [DocumentImportController::class, 'import'])->name('documents.import');
Route::post('documents/process-queue', [DocumentImportController::class, 'processQueue'])->name('documents.process.queue');
