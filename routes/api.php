<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculateController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/submit-cost', [CalculateController::class, 'submitCost'])->name('api.submit-cost');
Route::get('/get-spec/{id}', [CalculateController::class, 'getSpec'])->name('api.get-spec');
Route::get('/get-process/{id}', [CalculateController::class, 'getProcess'])->name('api.get-process');
Route::get('/get-process-rate/{proccess}/{proccess_code}', [CalculateController::class, 'getProcessRate'])->name('api.get-process-rate');
Route::get('/get-type-currency/{currency}', [CalculateController::class, 'getTypeCurrency'])->name('api.get-type-currency');
Route::get('/get-value-currency/{currency}/{type}/{date}', [CalculateController::class, 'getValueCurrency'])->name('api.get-value-currency');
Route::get('/get-mat-ex-rate/{date}/{currency}/{material}/{spec}', [CalculateController::class, 'getMatExRate'])->name('api.get-mat-ex-rate');

Route::post('/submit-cost-tmmin', [CalculateController::class, 'submitCostTmmin'])->name('api.submit-cost-tmmin');
