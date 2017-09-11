<?php

namespace App\Actions;

class TechAction extends Action
{
    public function whatLaravel()
    {
        $this->setCurrentState($this->request->getSender()->id, 'replyWhatLaravel');
        $this->messenger->text("hi friend, Whats your name?")->send($this->request->getSender()->id);
    }


    public function defaultReply()
    {

        file_put_contents(__DIR__ . '/../../log.txt', json_encode($_SESSION));
        if ($state = $this->getCurrentState($this->request->getSender()->id)) {
            $this->deleteCurrentState($this->request->getSender()->id);
            return call_user_func_array([$this, $state], []);
        }

        $this->messenger->text("Sorry I can't understand :(")->send($this->request->getSender()->id);
    }
}