<?php

namespace Nahid\FaceBot\Messengers\Templates;


class GenericTemplate extends BaseTemplate
{
    use Buttonable;

    public function __construct($heading, $subtitle = null, $imageUrl = null)
    {
        $elements["title"] = $heading;

        if (!is_null($subtitle)) {
            $elements["subtitle"] = $subtitle;
        }

        if (!is_null($imageUrl)) {
            $elements["image_url"] = $imageUrl;
        }

        $this->options["type"] = "template";
        $this->options["payload"] = [
            "template_type" => "generic",
            "elements" => [
                $elements
            ]
        ];
    }

    public function buttons()
    {
        $this->options['payload']["elements"][0]["buttons"] = [];

        return $this;
    }

    public function defaultAction($type, $url, $messengerExt = false, $webviewHeightRation = "full", $fallbackUrl = "")
    {
        $this->options["payload"]["elements"][0]["default_action"] = [
            "type" => $type,
            "url" => $url,
            "messenger_extensions" => $messengerExt,
            "webview_height_ratio" => $webviewHeightRation,
            "fallback_url" => $fallbackUrl
        ];

        return $this;
    }


    public function addShareButton(GenericTemplate $generic)
    {
        $button = [
            "type" => "element_share",
            "share_contents" => [
                "attachment" => $generic->getOptions()
            ]
        ];
        array_push($this->options["payload"]["elements"][0]['buttons'], $button);

        return $this;
    }

}