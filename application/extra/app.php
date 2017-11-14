<?php

return [
    'password_pre_halt' => '#salt',
    'aeskey' => 'sgg45747ss223455',
    'apptypes' => [
        'ios',
        'android',
    ],
    'app_sign_expire_time' => 600000000,         //sign失效时间
    'app_sign_cache_time' => 20,          // 缓存失效时间

    'message' => 'OK',                       // api正确时默认返回语句
    'login_time_out_day' => 7, //登录失效时间
];