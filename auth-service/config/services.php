<?php

return [
    'jwt_secret' => env('JWT_SECRET', 'phongkham_secret_key_jwt_microservices_2026_secure'),
    'gateway_url' => env('GATEWAY_URL', 'http://127.0.0.1:8000'),
    'dich_vu_xac_thuc' => env('DICH_VU_XAC_THUC_URL', 'http://127.0.0.1:8001'),
    'dich_vu_lich_hen' => env('DICH_VU_LICH_HEN_URL', 'http://127.0.0.1:8002'),
    'dich_vu_y_te' => env('DICH_VU_Y_TE_URL', 'http://127.0.0.1:8003'),
    'dich_vu_hoa_don' => env('DICH_VU_HOA_DON_URL', 'http://127.0.0.1:8004'),
];