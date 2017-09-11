<?php


if (!function_exists('_env')) {
    function _env($key = null, $default = null)
    {
        $env = new \Nahid\FaceBot\Env\EnvManager();

        if (is_null($key)) {
            return $env;
        }

        return $env->get($key, $default);
    }
}