<?php

namespace Nahid\FaceBot\Db;

use Nahid\FaceBot\Db\Redis;
use Nahid\FaceBot\Http\Request;

class Storage
{
    protected $db;
    protected $request;

    public function __construct()
    {
        $this->db = new Redis();
        $this->request = new Request();
    }

    public function setForm($field, $value)
    {
        $form_key = "form-" . $this->request->getSender()->id;
        $data = $this->db->get($form_key);

        if (!is_null($data)) {
            $data_assoc = json_decode($data, true);
        }else {
            $data_assoc = [];
        }

        $data_assoc[$field] = $value;

        $this->db->set($form_key, json_encode($data_assoc));
        return true;
    }

    public function getForm()
    {
        $form_key = "form-" . $this->request->getSender()->id;
        return $this->db->get($form_key);
    }

    public function removeForm()
    {
        $form_key = "form-" . $this->request->getSender()->id;
        return $this->db->delete($form_key);
    }



    public function setCurrentState($user, $state_name)
    {
        $this->db->set('user-state-' . $user, $state_name);
    }

    public function getCurrentState($user)
    {
        $user = "user-state-" . $user;
        if ($this->db->exists($user)) {
            return $this->db->get($user);
        }

        return null;
    }

    public function deleteCurrentState($user)
    {
        $user = "user-state-" . $user;
        if ($this->db->exists($user)) {
            return $this->db->delete($user);
        }

    }
}