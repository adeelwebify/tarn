<?php

use Tarn\Core\Session;

if (!function_exists('old')) {
    /**
     * Retrieve "old" input data from the previous request.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function old(string $key = null, mixed $default = '') {
        return Session::old($key, $default);
    }
}

if (!function_exists('session')) {
    /**
     * Get / set the specified session value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param array|string|null $key
     * @param mixed $default
     * @return mixed
     */
    function session(array|string|null $key = null, mixed $default = null) {
        if ($key === null) {
            return new Session;
        }

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                Session::put($k, $v);
            }
            return null;
        }

        return Session::get($key, $default);
    }
}
