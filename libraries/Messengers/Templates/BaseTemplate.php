<?php

namespace Nahid\FaceBot\Messengers\Templates;


class BaseTemplate
{
    protected $options;
    protected $currentOptions = null;


    public function getOptions()
    {
        return $this->options;
    }


    protected function setCurrentOptions(&$options, $last = true)
    {
        if (!$last) {
            $this->currentOptions = &$options;
        } else {
            $last_index = count($options) - 1;
            $this->currentOptions = &$options[$last_index];
        }

    }


    public function inPayload()
    {
        $this->setCurrentOptions($this->options["payload"], false);
        return $this;
    }

    public function inElements()
    {
        $this->setCurrentOptions($this->options["payload"]["elements"], false);
        return $this;
    }

    public function defaultAction($type, $url, $messengerExt = false, $webviewHeightRation = "full", $fallbackUrl = null)
    {

        if (isset($this->currentOptions)) {
            $this->currentOptions["default_action"] = [
                "type" => $type,
                "url" => $url,
                "webview_height_ratio" => $webviewHeightRation
            ];

            if ($messengerExt == true) {
                $this->currentOptions["default_action"]["messenger_extensions"] = true;
            }

            if (!is_null($fallbackUrl)) {
                $this->currentOptions["default_action"]["fallback_url"] = $fallbackUrl;
            }
        }

        return $this;
    }

}