<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'dich_vu' => 'appointment-service',
        'trang_thai' => 'DANG_HOAT_DONG',
        'port' => 8002,
        'co_so_du_lieu' => 'db_benh_nhan_lich_hen'
    ]);
});
