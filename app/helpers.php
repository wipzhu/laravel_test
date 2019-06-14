<?php

use Illuminate\Contracts\Routing\ResponseFactory;

if (!function_exists('pr')) {
    function pr($arr)
    {
        if (!empty($arr)) {
            echo "<pre>";
            print_r($arr);
            echo "<pre/>";
        } else {
            echo "pr数组为空";
        }
    }
}
