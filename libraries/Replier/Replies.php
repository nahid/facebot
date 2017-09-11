<?php

namespace Nahid\FaceBot\Replier;

use Closure;
use Nahid\FaceBot\Http\Request;
use Predis\Client;

class Replies
{
    protected $namespace = "";
    protected $default;
    protected $replies = [
        "message" => [
            "text"  => [
                "action"=>"Namespace/ExampleReplies@action"
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
            $replies = $text;
        } else {
            $replies = explode('|', $text);
        }

        foreach ($replies as $reply) {
            $this->replies["message"][strtolower($reply)] = [
                "action"    => $action
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

            $message = $request->getMessage();

            $namespace = "\\" . $this->namespace;

            if (array_key_exists($message->text, $this->replies["message"])) {
                $action = $this->replies["message"][strtolower($message->text)]["action"];
                $handler = explode('@', $action);
                $class = $namespace . "\\" . $handler[0];
                $method = $handler[1];

                $instance = new $class();

                call_user_func_array([$instance, $method], []);

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