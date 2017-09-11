<?php

namespace Nahid\FaceBot\Env;

use josegonzalez\Dotenv\Loader;

class EnvManager
{
    protected $env;
    protected $parse;


    public function __construct()
    {
        $this->parse();
    }

    public function parse()
    {
        $env = new Loader(__DIR__ . "/../../.env");

        $this->parse = $env->parse();
        $this->env = $this->parse->toArray();
    }

    public function has($key)
    {
        if (isset($this->env[$key])) {
            return true;
        }

        return false;
    }

    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->env[$key];
        }

        return $default;
    }
}