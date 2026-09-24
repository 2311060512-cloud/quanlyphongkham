<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'dich_vu' => 'clinical-service',
        'trang_thai' => 'DANG_HOAT_DONG',
        'port' => 8003,
        'co_so_du_lieu' => 'db_dich_vu_y_te'
    ]);
});
