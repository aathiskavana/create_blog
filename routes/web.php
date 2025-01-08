<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/', [SparePartController::class, 'index'])->name('spare-parts.index');
Route::get('/spare-parts/create', [SparePartController::class, 'create'])->name('spare-parts.create');
Route::post('/spare-parts', [SparePartController::class, 'store'])->name('spare-parts.store');
Route::get('/spare-parts/{sparePart}', [SparePartController::class, 'show'])->name('spare-parts.show');
Route::get('/spare-parts/{sparePart}/edit', [SparePartController::class, 'edit'])->name('spare-parts.edit');
Route::put('/spare-parts/{sparePart}', [SparePartController::class, 'update'])->name('spare-parts.update');
Route::delete('/spare-parts/{sparePart}', [SparePartController::class, 'destroy'])->name('spare-parts.destroy');
Route::get('/spare-parts/report', [SparePartController::class, 'report'])->name('spare-parts.report');
