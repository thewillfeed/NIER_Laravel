<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers;
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

Route::get('/', '\App\Http\Controllers\MainController@index')->middleware(['auth'])->name('main');



Route::post('/create', '\App\Http\Controllers\MessageController@create');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/welcome', function () {
    return view('welcome');
})->middleware(['auth'])->name('welcome');


require __DIR__.'/auth.php';
