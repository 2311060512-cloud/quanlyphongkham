<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'dich_vu' => 'billing-service',
        'trang_thai' => 'DANG_HOAT_DONG',
        'port' => 8004,
        'co_so_du_lieu' => 'db_hoa_don_thanh_toan'
    ]);
});
