<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/trang-chu', function () {
    return view('dashboard');
});

Route::get('/dat-lich', function () {
    return view('dashboard');
});

Route::get('/admin', function () {
    return view('dashboard');
});

Route::get('/bac-si', function () {
    return view('dashboard');
});

Route::get('/benh-nhan', function () {
    return view('dashboard');
});

Route::get('/nguoi1', function () {
    return view('nguoi1');
});

Route::get('/nguoi2', function () {
    return view('nguoi2');
});

Route::get('/dang-nhap', function () {
    return view('auth');
});

Route::get('/dang-ky', function () {
    return view('auth');
});

Route::get('/auth', function () {
    return view('auth');
});
