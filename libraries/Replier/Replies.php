<?php

namespace Nahid\FaceBot\Replier;

use Closure;
use Nahid\FaceBot\Db\Storage;
use Nahid\FaceBot\Http\Request;
use Nahid\FaceBot\Messengers\Message;

class Replies
{
    protected $namespace = "";
    protected $default;
    protected $replies = [
        "message" => [
            "text"  => [
                "action"=>"Namespace\ExampleReplies@action"
            ]
        ],
        "postback"=>[
            "buy:type" => [
                "action"=> "Namespace\ExampleReplies@action"
            ]
        ],
        "delivered" => [

        ],
        "echo" => [

        ],
        "read" => [

        ]

    ];

    public function register($namespace, Closure $fun)
    {
        $this->namespace = $namespace;
        call_user_func($fun, $this);
    }


    public function listen($text, $action)
    {
        if (is_array($text)) {
            $listens = $text;

            foreach ($listens as $listen) {
                $this->replies["message"][strtolower($listen)] = [
                    "action"    => $action
                ];
            }
        } else {
            $this->replies["message"][strtolower($text)] = [
                "action"    => $action
            ];
        }

        return $this;
    }

    public function listenPostback($command, $action)
    {
        $this->replies["postback"][strtolower($command)] = [
            "action"    => $action
        ];

        return $this;
    }

    public function listenAndText($text, $reply)
    {

        if (is_array($text)) {
            $listens = $text;

            foreach ($listens as $listen) {
                $this->replies["message"][strtolower($listen)] = [
                    "text"    => $reply
                ];
            }
        } else {
            $this->replies["message"][strtolower($text)] = [
                "text"    => $reply
            ];
        }



        return $this;
    }

    public function defaultReply($action)
    {
        $this->default = $action;
    }

    public function dispacth(Request $request)
    {

        if ($request->hasMessageAndNoEcho()) {
            $this->generalReply($request);
        }

        if ($request->getPostback()) {
            $this->postbackReply($request);
        }

    }

    private function generalReply(Request $request)
    {
        $storage = new Storage();
        $action = $storage->getCurrentState($request->getSender()->id);
        if (!is_null($action)) {
            return $this->dispatchAction($action);
        }

        $message = $request->getMessage();

        $namespace = "\\" . $this->namespace;

        if (array_key_exists($message->text, $this->replies["message"])) {
            if (isset($this->replies["message"][strtolower($message->text)]["text"])) {
                $text = $this->replies["message"][strtolower($message->text)]["text"];
                $message = new Message();
                $request = new Request();
                $response = $message->text($text)->send($request->getSender()->id);
            } else {
                $action = $this->replies["message"][strtolower($message->text)]["action"];
                $handler = explode('@', $action);
                $class = $namespace . "\\" . $handler[0];
                $method = $handler[1];

                $instance = new $class();

                call_user_func_array([$instance, $method], []);
            }

        } elseif ($actions = $this->regexMatcher(strtolower($message->text))) {
            $action = $actions["value"]["action"];
            $handler = explode('@', $action);
            $class = $namespace . "\\" . $handler[0];
            $method = $handler[1];

            $instance = new $class();

            call_user_func_array([$instance, $method], $actions["params"]);
        } else {
            $action = $this->default;
            $handler = explode('@', $action);
            $class = $namespace . "\\" . $handler[0];
            $method = $handler[1];

            $instance = new $class();

            call_user_func_array([$instance, $method], []);
        }
    }

    protected function dispatchAction($action)
    {

        $namespace = "\\" . $action;

        $handler = explode('@', $namespace);
        $class = $handler[0];
        $method = $handler[1];

        $instance = new $class();

        return call_user_func_array([$instance, $method], []);


    }

    private function postbackReply(Request $request)
    {
        $postback = $request->getPostback();
        $payload = $request->getPostbackPayload();

        $command = strtolower($payload->type. ":". $postback->title);
        $namespace = "\\" . $this->namespace;

        if (array_key_exists($command, $this->replies["postback"])) {
                $action = $this->replies["postback"][$command]["action"];
                $handler = explode('@', $action);
                $class = $namespace . "\\" . $handler[0];
                $method = $handler[1];

                $instance = new $class();
                call_user_func_array([$instance, $method], []);

        }
    }


    protected function makeRegex($pattern)
    {
        // check invalid pattern

        if (preg_match('/[^-:\/_{}()a-zA-Z\s\d]/', $pattern)) {
            return false;
        }

        $allowedParamChars = '[a-zA-Z0-9\-\_]+';

        // Create capture group for '{parameter}'

        $pattern = preg_replace(
            '/{('. $allowedParamChars .')}/',    # Replace "{parameter}"
            '(?<$1>' . $allowedParamChars . ')', # with "(?<parameter>[a-zA-Z0-9\_\-]+)"
            $pattern
        );

        // Add start and end matching

        $patternAsRegex = "@^" . $pattern . "$@D";

        return $patternAsRegex;
    }


    protected function regexMatcher($command)
    {

        foreach ($this->replies["message"] as $cmd => $value) {
            // Make regexp from text
            $patternAsRegex = $this->makeRegex($cmd);
            if ($ok = !!$patternAsRegex) {
                // We've got a regex, let's parse a Text
                if ($ok = preg_match($patternAsRegex, $command, $matches)) {
                    // Get elements with string keys from matches
                    $params = array_intersect_key(
                        $matches,
                        array_flip(array_filter(array_keys($matches), 'is_string'))
                    );

                   if ($ok) {
                       return [
                           "params"=>$params,
                           "value"  => $value
                       ];
                   }
                }
            }
        }

        return false;
    }

}