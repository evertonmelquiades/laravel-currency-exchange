<?php


use App\Http\Controllers\CurrencyController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Currency');
});
Route::get('/limit-reached', function () {
    return Inertia::render('Limit');
})->name('limitReached');

//Route::get('/limit-reached', [CurrencyController::class, 'limitReached'])->name('limitReached');
Route::post('/currency', [CurrencyController::class, 'exchangeCurrency']);
