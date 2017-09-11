<?php

namespace Nahid\FaceBot\Messengers;

class ShareButton
{
    protected $options = [
        "type" => "element_share",
        "share_contents" => []
    ];


    public function attachment()
    {
        $this->options["share_contents"]["attachment"] = [
            "type"=>"template",
            "payload"=> []
        ];

        return $this;
        
    }


}