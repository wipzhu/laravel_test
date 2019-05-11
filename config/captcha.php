<?php
/*
安装步骤：

composer 安装：composer require mews/captcha

注册providers （config/app.php） ，在这个数组中的最后追加如下代码：
    Mews\Captcha\CaptchaServiceProvider::class,

注册aliases （config/app.php），在这个数组中的最后追加如下代码：
    'Captcha' => Mews\Captcha\Facades\Captcha::class,

生成配置文件，在 Composer 命令行中输入如下命令：
    php artisan vendor:publish

进入config/captcha.php 文件，修改default 数组 可以对验证码进行样式、数量、大小上的修改。
*/

return [
    'characters' => ['2', '3', '4', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'm', 'n', 'p', 'q', 'r', 't', 'u', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'M', 'N', 'P', 'Q', 'R', 'T', 'U', 'X', 'Y', 'Z',
    ],
    'default' => [
        'length' => 4,
        'width' => 180,
        'height' => 42,
        'quality' => 60,
        'math' => false,
    ],
    'math' => [
        'length' => 9,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'math' => true,
    ],

    'flat' => [
        'length' => 6,
        'width' => 160,
        'height' => 46,
        'quality' => 90,
        'lines' => 6,
        'bgImage' => false,
        'bgColor' => '#ecf2f4',
        'fontColors' => ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'],
        'contrast' => -5,
    ],
    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
    ],
    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => true,
        'contrast' => -5,
    ]
];
