<?php

namespace Nahid\FaceBot\Env;

use Dotenv\Dotenv;
use Nahid\FaceBot\Messengers\Message;

class EnvManager
{
    protected $env;
    protected $parse;
    protected $message;
    protected $path;


    public function __construct($path)
    {
        $this->path = $path;
        $this->parse();
    }

    public function parse()
    {
        $env = new Dotenv($this->path);
        $env->load();

    }

  /*  public function has($key)
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
    }*/
}