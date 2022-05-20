<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\SubCategoryController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ReportController;

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

Route::middleware('auth:sanctum')->group( function () {
    Route::post('create-categoty', [CategoryController::class, 'store']);
    Route::post('create-subcategoty', [SubCategoryController::class, 'store']);

    Route::get('get-user-transactions', [TransactionController::class, 'index']);
    Route::post('create-transaction', [TransactionController::class, 'store']);
    Route::get('show-transaction/{id}', [TransactionController::class, 'show']);

    Route::post('create-payment', [PaymentController::class, 'store']);

    Route::get('show-baisic-report', [ReportController::class, 'showBaisicReport']);
    Route::get('show-monthly-report', [ReportController::class, 'showMonthlyReport']);

});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

