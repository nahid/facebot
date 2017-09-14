<?php
require 'vendor/autoload.php';

use Nahid\FaceBot\Replier\Replies;
use Nahid\FaceBot\Http\Request;
use  Nahid\FaceBot\Env\EnvManager;

$env = new EnvManager(__DIR__);
$replier = new Replies();
$request = new Request();


if ($token = $request->verifyToken()) {
    echo $token;
    die();
}

$replier->register('App\Actions', function($replier) {
    require __DIR__ . "/app/replies.php";
});

$replier->dispacth($request);
