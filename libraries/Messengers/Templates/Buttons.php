<?php

namespace Nahid\FaceBot\Messengers\Templates;

trait Buttons
{

    public function addButtonUrl($title, $url, $options = [])
    {
        return $this->makeButton('web_url', $title, $url, $options);

    }

    public function addButtonPostback($title, $type, $payload)
    {
        if (isset($this->options["payload"]['buttons'])) {
            $payload = array_merge($payload, ["type"=> $type]);
            $button = [
                "type" => "postback",
                "payload" => json_encode($payload),
                "title" => $title
            ];


            $this->pushButton($button);

            return $this;
        }

    }

    public function addButtonCall($title, $payload)
    {
        if (substr($payload, 0, 1) != '+') {
            $payload = '+' . $payload;
        }

        $button = [
            "type" => "phone_number",
            "payload" => $payload,
            "title" => $title
        ];


       $this->pushButton($button);

        return $this;

    }

    protected function makeButton($type, $title, $url, $options = [])
    {

            $button = [
                "type" => $type,
                "url" => $url,
                "title" => $title
            ];

            $button = array_merge($button, $options);

            $this->pushButton($button);

            return $this;

    }

    protected function pushButton($button)
    {

        if (isset($this->currentOptions)) {
            if (isset($this->currentOptions["buttons"])) {
                array_push($this->currentOptions["buttons"], $button);
            }else {
                $this->currentOptions["buttons"][] = $button;
            }

        }

        if (is_null($this->currentOptions)) {
            $this->options["payload"]["buttons"][] = $button;
        }
    }
}