<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function (\Illuminate\Http\Request $request) {
    if ($request->wantsJson()) {
        return response()->json([
            'dich_vu' => 'auth-service',
            'trang_thai' => 'DANG_HOAT_DONG',
            'port' => 8001,
            'co_so_du_lieu' => 'db_xac_thuc_bac_si'
        ]);
    }
    return view('nguoi1');
});

Route::get('/portal', function () {
    return view('nguoi1');
});

Route::get('/dang-nhap', function () {
    return view('auth');
});

Route::get('/dang-ky', function () {
    return view('auth');
});
