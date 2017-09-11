<?php

namespace Nahid\FaceBot\Messengers\Templates;


class ButtonTemplate extends BaseTemplate
{
    use Buttons;

    public function __construct($heading)
    {
        $this->options["type"] = "template";
        $this->options["payload"] = [
            "template_type" => "button",
            "text" => $heading,
            "buttons" => []
        ];
    }

    public function getButtonsOption()
    {
        return $this->options['payload']['buttons'];
    }



}