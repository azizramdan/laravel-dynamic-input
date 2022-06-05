<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [TransactionController::class, 'index']);
Route::get('create', [TransactionController::class, 'create']);
Route::post('/', [TransactionController::class, 'store']);
Route::delete('/{transaction}', [TransactionController::class, 'destroy']);
Route::get('/{transaction}/edit', [TransactionController::class, 'edit']);
Route::patch('/{transaction}', [TransactionController::class, 'update']);
