<?php

namespace Nahid\FaceBot\Http;

class Response
{
    protected $response;

    public function __construct(\Apiz\Http\Response $response)
    {
        $this->response = $response;
    }
}