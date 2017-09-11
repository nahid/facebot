<?php

namespace Nahid\FaceBot\Messengers\Templates;


class ListTemplate extends BaseTemplate
{
    use Buttons;



    public function __construct($element_style = "large")
    {
        $this->options["type"] = "template";
        $this->options["payload"] = [
            "template_type" => "list",
            "top_element_style" => $element_style,
            "elements" => [],
            "buttons" => []
        ];
    }

    public function getListOptions()
    {
        return $this->options;
    }

    public function addBanner($title, $sub_title, $image_url)
    {
        $this->options["payload"]["elements"][]=[
            "title" => $title,
            "subtitle" => $sub_title,
            "image_url" => $image_url
        ];

        $this->setCurrentOptions($this->options["payload"]["elements"]);
        return $this;
    }


    public function addList($title, $sub_title, $image_url = "")
    {
        $this->options["payload"]["elements"][]=[
            "title" => $title,
            "subtitle" => $sub_title,
            "image_url" => $image_url
        ];

        $this->setCurrentOptions($this->options["payload"]["elements"]);
        return $this;
    }



}