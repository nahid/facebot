<?php

namespace App\Actions;

class TalkAction extends Action
{
    public function hello()
    {
        $this->setCurrentState($this->request->getSender()->id, $this->makeState('askName'));
        $resp = $this->messenger->text("write your latitude and longitude \n example: 
        ```\nlat: 20.3457 long: 93.389457845 \n```")->send($this->request->getSender()->id);

        //dump($resp);
    }

    public function locations($lat, $lng)
    {
        $this->messenger->text("Thank you for sharing your location.\n
        Latitude: {$lat} and Longitude: {$lng}")->send($this->request->getSender()->id);
    }

    public function howAreYou()
    {
        $this->messenger->text("I'm fine you?")->send($this->request->getSender()->id);
    }

    public function iAm($name)
    {
        $this->messenger->text("You are " . ucfirst($name))->send($this->request->getSender()->id);
    }

    public function thanks()
    {
        $this->messenger->text("You are most welcome :)")->send($this->request->getSender()->id);
    }

    public function born($year)
    {
        $age = date('Y') - $year;
        $this->messenger->text("Your age " . $age . " years")->send($this->request->getSender()->id);
    }

    public function country($country)
    {
        $this->messenger->text("You are from " . $country)->send($this->request->getSender()->id);
    }


    public function identity($name, $country)
    {
        $this->messenger->text("Great ". ucfirst($name) ." you are from " . ucfirst($country))->send($this->request->getSender()->id);
    }

    public function eid()
    {
        $this->messenger->image("http://s3.india.com/wp-content/uploads/2015/09/Eid-Mubarak-Pictures.jpg")
            ->send($this->request->getSender()->id);
    }

    public function fine()
    {
        $this->messenger->text("great, thank you :)")->send($this->request->getSender()->id);
    }

    public function whoAreYou()
    {
        $this->messenger->text("I'm FaceBot")->send($this->request->getSender()->id);
    }

    public function gender()
    {
        $this->messenger->text("Nope, I'm male")->send($this->request->getSender()->id);
    }

    public function askName()
    {
        //dump("paichi");
        $this->messenger->text("Thank you " . $this->request->getMessage()->text . " for your response")->send($this->request->getSender()->id);
    }

    public function where()
    {
        $this->messenger->text("I'm from Earth")->send($this->request->getSender()->id);
    }

    public function menu()
    {
        $this->messenger
            ->buttonTemplate("Choose blog site")
            ->addButtonUrl("Nahid Diary", "http://nahid.im/diary")
            ->addButtonUrl("PHP Manual", "http://php.net/manual")
            ->addButtonUrl("Laravel Doc", "http://laravel.com/doc");

        $this->messenger->send($this->request->getSender()->id);
        dump($this->messenger);
    }

    public function mePhoto()
    {
        $this->messenger->image("https://scontent.fdac6-1.fna.fbcdn.net/v/t1.0-1/p200x200/20729531_1444615592284046_8679306088181997312_n.jpg?oh=a9e978226c9104b35ebe141f1e9a3fd0&oe=5A1B678D")
            ->send($this->request->getSender()->id);
    }

    public function lists()
    {
        $list = $this->messenger->listTemplate("large");

        $list->addBanner("My own shop", "World best Collection", "https://about.canva.com/wp-content/uploads/sites/3/2017/02/congratulations_-banner.png")
            ->addButtonUrl("View", "http://nahid.im", [
                "webview_height_ratio" => "full",
            ])->defaultAction("web_url", "http://nahid.im");

        $list->addList("Macbook Pro", "Early 2015", "https://store.storeimages.cdn-apple.com/4974/as-images.apple.com/is/image/AppleInc/aos/published/images/m/ac/macbook/air/macbook-air-select-201706?wid=452&hei=420&fmt=jpeg&qlt=95&op_sharpen=0&resMode=bicub&op_usm=0.5,0.5,0,0&iccEmbed=0&layer=comp&.v=1496085621130")
            ->addButtonUrl("Buy Now", "http://nahid.im")
            ->defaultAction("web_url", "http://nahid.im");

        $list->addList("Macbook Air", "Early 2016", "https://store.storeimages.cdn-apple.com/4974/as-images.apple.com/is/image/AppleInc/aos/published/images/m/ac/macbook/air/macbook-air-select-201706?wid=452&hei=420&fmt=jpeg&qlt=95&op_sharpen=0&resMode=bicub&op_usm=0.5,0.5,0,0&iccEmbed=0&layer=comp&.v=1496085621130")
            ->addButtonUrl("Buy Now", "http://nahid.im")
            ->defaultAction("web_url", "http://nahid.im");

        $list->inPayload()->addButtonPostback("View More", "nothing");

        $resp = $this->messenger->send($this->request->getSender()->id);
        dump($resp);

    }

    public function buy()
    {
        $this->messenger->quickReplies("Choose size")
            ->location()
            /*
            ->addText("Medium", "")
            ->addText("Small", "")*/
            ->send($this->request->getSender()->id);
    }


    public function defaultReply()
    {

        //file_put_contents(__DIR__ . '/../../log.txt', json_encode($_SESSION));
        if ($state = $this->getCurrentState($this->request->getSender()->id)) {
            $this->deleteCurrentState($this->request->getSender()->id);
            return call_user_func_array([$this, $state], []);
        }

        $this->messenger->text("Sorry I can't understand :(")->send($this->request->getSender()->id);
    }
}