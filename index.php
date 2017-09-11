<?php
require 'vendor/autoload.php';

use Nahid\FaceBot\Replier\Replies;
use Nahid\FaceBot\Http\Request;


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
/*
use Nahid\FaceBot\Messengers\Templates\ListTemplate;

$list = new ListTemplate();

$list->addBanner("My Shop", "http://my.shop")
    ->defaultAction("web_url", "http://google.com")
    ->addButtonUrl("Buy", "http://my.shop/1");

$list->addList("The Globe", "Everything in one place")
    ->addButtonUrl("Buy Now", "http://my.shop/2");

$list->addList("Xiaomi Redmi 4x", "China iPhone")
    ->addButtonCall("Call Now", "8801848044143")
    ->addButtonUrl("Buy Now", "http://my.shop/2");

$list->inPayload()
    ->addButtonUrl("See All", "http://fb.me")
    ->addButtonUrl("View All", "http://fb.me");

$x = $list->getListOptions();
print(json_encode($x));*/
