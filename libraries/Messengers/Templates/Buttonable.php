<?php

namespace Nahid\FaceBot\Messengers\Templates;

trait Buttonable
{

    public function addButtonUrl($title, $url, $options = [])
    {
        return $this->makeButton('web_url', $title, $url, $options);

    }

    public function addButtonPostback($title, $payload)
    {
        if (isset($this->options["payload"]['buttons'])) {

            $button = [
                "type" => "postback",
                "payload" => $payload,
                "title" => $title
            ];


            if (isset($this->options["payload"]['buttons'])) {
                array_push($this->options["payload"]['buttons'], $button);
            }

            if (isset($this->options["payload"]["elements"][0]['buttons'])) {
                array_push($this->options["payload"]["elements"][0]['buttons'], $button);
            }

            return $this;
        }

    }

    public function addButtonCall($title, $payload)
    {
        if (substr($payload, 0, 1) != '+') {
            $payload = '+' . $payload;
        }

        if (isset($this->options["payload"]['buttons'])) {

            $button = [
                "type" => "phone_number",
                "payload" => $payload,
                "title" => $title
            ];


            if (isset($this->options["payload"]['buttons'])) {
                array_push($this->options["payload"]['buttons'], $button);
            }

            if (isset($this->options["payload"]["elements"][0]['buttons'])) {
                array_push($this->options["payload"]["elements"][0]['buttons'], $button);
            }

            return $this;
        }

    }

    protected function makeButton($type, $title, $url, $options = [])
    {

            $button = [
                "type" => $type,
                "url" => $url,
                "title" => $title
            ];

            $button = array_merge($button, $options);
            if (isset($this->options["payload"]['buttons'])) {
                array_push($this->options["payload"]['buttons'], $button);
            }

            if (isset($this->options["payload"]["elements"][0]['buttons'])) {
                array_push($this->options["payload"]["elements"][0]['buttons'], $button);
            }


            return $this;

    }
}