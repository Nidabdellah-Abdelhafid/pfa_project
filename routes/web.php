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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/untitled', function () {
    return view('untitled');
});

Route::get('/form', function () {
    return view('form');
});

Route::get('/vaccinated/admin/login', function () {
    return view('/vaccinated/admin/login');
});

Route::get('scraper','ScraperController@scraper');
