<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'dich_vu' => 'auth-service',
        'trang_thai' => 'DANG_HOAT_DONG',
        'port' => 8001,
        'co_so_du_lieu' => 'db_xac_thuc_bac_si'
    ]);
});
