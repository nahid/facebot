<?php

namespace Nahid\FaceBot\Messengers;

use Apiz\AbstractApi;

class ApiClient extends AbstractApi
{
    protected $prefix = 'v2.6';

    protected $options = [
        "recipient", "message"
    ];

    public function setBaseUrl()
    {
        return "https://graph.facebook.com";
    }

    public function getAccessToken()
    {
        return _env("PAGE_ACCESS_TOKEN");
    }

    public function subscribe()
    {
        return $this->query([
            'access_token'=> $this->getAccessToken()
        ])->post('/me/subscribed_apps');
    }

    public function me($id, array $fields)
    {
        $fields = implode(',', $fields);
        return $this->query([
            "fields"=>$fields,
            "access_token"=> $this->getAccessToken()
        ])->get($id);
    }


    public function sendMessage($options)
    {
        return $this->query([
            'access_token'=> $this->getAccessToken()
        ])->json($options)->post('me/messages');

    }

}