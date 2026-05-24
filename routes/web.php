<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('splash');
});

Route::get('/home', function () {
    return view('lotteria');
})->name('home');

Route::get('/order', function () {
    return view('order');
})->name('order');

Route::get('/franchising', function () {
    return view('franchising');
})->name('franchising');

Route::get('/unavailable', function () {
    return view('unavailable');
})->name('unavailable');

Route::get('/order', function () {
    return view('order');
})->name('order');

Route::get('/franchising', function () {
    return view('franchising');
})->name('franchising');
