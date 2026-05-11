<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RaspadinhasController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WithdrawalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/raspadinhas/search', [RaspadinhasController::class, 'search'])->name('api.raspadinhas.search');
Route::get('/raspadinhas', [RaspadinhasController::class, 'index'])->name('api.raspadinhas.index');
Route::get('/raspadinhas/{raspadinha}', [RaspadinhasController::class, 'show'])->name('api.raspadinhas.show');
Route::get('/deposit/limits', [DepositController::class, 'getLimits']);
Route::get('/withdraw/limits', [WithdrawalController::class, 'getLimits']);

Route::withoutMiddleware(['web', 'csrf'])->group(function () {
    Route::post('/webhookprimebank', [DepositController::class, 'webhook']);
});
