<?php


if (!function_exists('pr')) {
    function pr($arr)
    {
        if (is_array($arr) && !empty($arr)) {
            echo "<pre>";
            print_r($arr);
            echo "<pre/>";
        } else {
            echo "pr数组为空";
        }
    }
}