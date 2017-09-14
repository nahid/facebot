<?php

namespace Nahid\FaceBot\Messengers;

use Nahid\FaceBot\Http\Response;
use Nahid\FaceBot\Messengers\ApiClient;
use Nahid\FaceBot\Messengers\Templates\ButtonTemplate;
use Nahid\FaceBot\Messengers\Templates\GenericTemplate;
use Nahid\FaceBot\Messengers\Templates\ListTemplate;

class Message
{
    protected $envPath;
    protected $api;
    protected $options = [
        "recipient", "message"
    ];

    protected $template;

    public function __construct()
    {
        $this->api = new ApiClient();
    }

    public function text($message)
    {
        $this->options['message'] ["text"] = $message;
        return $this;
    }

    public function image($url, $is_reusable = false)
    {
        $this->options['message']["attachment"] = [
            "type"  => "image",
            "payload" => [
                "url"=>$url,
                "is_reusable" => $is_reusable
            ]
        ];

        return $this;
    }

    public function audio($url, $is_reusable = false)
    {
        $this->options['message']["attachment"] = [
            "type"  => "audio",
            "payload" => [
                "url"=>$url,
                "is_reusable" => $is_reusable
            ]
        ];

        return $this;
    }

    public function file($url, $is_reusable = false)
    {
        $this->options['message']["attachment"] = [
            "type"  => "file",
            "payload" => [
                "url"=>$url,
                "is_reusable" => $is_reusable
            ]
        ];

        return $this;
    }

    public function video($url, $is_reusable = false)
    {
        $this->options['message']["attachment"] = [
            "type"  => "video",
            "payload" => [
                "url"=>$url,
                "is_reusable" => $is_reusable
            ]
        ];

        return $this;
    }

    private function upload($file)
    {
        $mimeTypes = include(__DIR__ . '../mime.php');

        $mime = $this->getMimeType($file);

        if (in_array($mime, $mimeTypes['supported'])) {
            $type = $this->getContentType($mime);
            $extension = $mimeTypes['supported'][$mime];
        }

    }

    public function buttonTemplate($heading)
    {

        $this->template = new ButtonTemplate($heading);
        return $this->template;

    }

    public function genericTemplate($heading, $subtitle = null, $imageUrl = null)
    {
        $this->template = new GenericTemplate($heading, $subtitle, $imageUrl);
        return $this->template;
    }

    public function listTemplate($element_style = "large")
    {
        $this->template = new ListTemplate($element_style);
        return $this->template;
    }


    public function quickReplies($text)
    {
        $this->options['message']["text"] = $text;
        $this->options['message']["quick_replies"] = [];

        return $this;
    }

    public function addText($title, $payload)
    {
        if (isset($this->options['message']["quick_replies"])) {
            $reply = [
                "content_type" => "text",
                "title" => $title,
                "payload" => $payload
            ];

            array_push($this->options['message']["quick_replies"], $reply);
        }

        return $this;
    }

    public function addTextWithImage($title, $image_url, $payload)
    {
        if (isset($this->options['message']["quick_replies"])) {
            $reply = [
                "content_type" => "text",
                "title" => $title,
                "image_url" => $image_url,
                "payload" => $payload
            ];

            array_push($this->options['message']["quick_replies"], $reply);
        }

        return $this;
    }

    public function location()
    {
        if (isset($this->options['message']["quick_replies"])) {
            $reply = [
                "content_type" => "location"
            ];

            array_push($this->options['message']["quick_replies"], $reply);
        }

        return $this;
    }


    public function send($recipient_id)
    {
        $this->options['recipient']["id"] = $recipient_id;
        if ($this->template instanceof \Nahid\FaceBot\Messengers\Templates\BaseTemplate) {
            $this->options['message']['attachment'] = $this->template->getOptions();
        }


        return new Response($this->api->sendMessage($this->options));
    }


    protected function getMimeType($file)
    {
        $result = new finfo();

        if (is_resource($result) === true) {
            return $result->file($file, FILEINFO_MIME_TYPE);
        }

        return false;
    }

    protected function getContentType($mime)
    {
        $type = explode('/', $mime);

        if (count($type) > 1) {
            return $type[0];
        }

        return false;
    }


    public function setEnvPath($path)
    {
        $this->envPath = rtrim($path, '/') . '/';

    }

    public function getEnvPath()
    {
        return $this->envPath;
    }


}