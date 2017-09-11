<?php

namespace App\Actions;

use Nahid\FaceBot\Db\Redis;
use Nahid\FaceBot\Http\Request;
use Nahid\FaceBot\Messengers\Message;

class Action
{

    protected $request;
    protected $messenger;
    protected $currentState;
    protected $db;

    public function __construct()
    {
        $this->request = new Request();
        $this->messenger = new Message();
        $this->db = new Redis();
    }


    protected function setCurrentState($user, $state_name)
    {
        $this->db->set('user-' . $user, $state_name);
    }

    protected function getCurrentState($user)
    {
        $user = "user-" . $user;
        if ($this->db->exists($user)) {
            return $this->db->get($user);
        }

        return null;
    }

    protected function deleteCurrentState($user)
    {
        $user = "user-" . $user;
        if ($this->db->exists($user)) {
            return $this->db->delete($user);
        }

    }

    public function defaultAction()
    {
        if ($this->request->hasMessageAndNoEcho()) {
            $state = $this->getCurrentState($this->request->getSender()->id);

            if (!is_null($state)) {
                $this->deleteCurrentState($this->request->getSender()->id);
                $action = explode('@', $state);
                $instance = new $action[0]();
                return call_user_func_array([$instance, $action[1]], []);
            } else {
                $this->messenger->text("Sorry I can't recognize your text :(")
                    ->send($this->request->getSender()->id);
            }

        }
    }

    protected function makeState($name)
    {
        $fullClass = explode("@", $name);

        if (count($fullClass) > 1) {
            return $name;
        }

        return get_class($this) . "@" . $name;
    }

}