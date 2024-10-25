<?php

use App\Http\Controllers\PrintController;
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

Route::get('/login', fn () => to_route('filament.dashboard.auth.login'))->name('login');
Route::get('/', fn () => to_route('filament.dashboard.auth.login'))->name('home');

Route::controller(PrintController::class)->prefix('prints')->name('prints.')->group(function () {
    Route::get('quotation/{quotation}', 'quotation')->name('quotation');
    Route::get('quotation/{quotation}/preview', 'preview')->name('preview');
});
