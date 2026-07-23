<?php

if (!function_exists('toastr')) {
    function toastr(?string $message = null, string $type = 'success'): \App\Helpers\Toastr
    {
        $instance = app(\App\Helpers\Toastr::class);
        if ($message) {
            return $instance->$type($message);
        }
        return $instance;
    }
}
