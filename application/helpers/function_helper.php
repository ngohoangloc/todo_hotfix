<?php

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle)
    {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool
    {
        $needle_len = strlen($needle);
        return ($needle_len === 0 || 0 === substr_compare($haystack, $needle, -$needle_len));
    }
}

if (!function_exists('dd')) {
    if (!function_exists('dd')) {
        function dd()
        {
            echo '<pre>';
            array_map(function ($x) {
                var_dump($x);
            }, func_get_args());
            die;
        }
    }
}
