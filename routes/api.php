<?php

use App\Http\Controllers\EnrollmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::controller(EnrollmentController::class)->group(function () {
    Route::get('/enrollments', 'index')->name('index');
    Route::get('/enrollment/create', 'create')->name('create');
    Route::post('/enrollment/create', 'store')->name('store');
    Route::get('/enrollment/{enrollment}', 'show')->name('show');
    Route::put('/enrollment/{enrollment}', 'update')->name('update');
    Route::delete('/enrollment/{enrollment}', 'delete')->name('delete');
});
