<?php

$replier->defaultReply("Action@defaultAction");

$replier->listen("i am {name}", "TalkAction@iAm");
$replier->listen("hello", "TalkAction@hello");
$replier->listen("how are you", "TalkAction@howAreYou");
$replier->listen("fine", "TalkAction@fine");
$replier->listen("who are you", "TalkAction@whoAreYou");
$replier->listen("are you female", "TalkAction@gender");
$replier->listen("where from you", "TalkAction@where");
$replier->listen("menu|show menu", "TalkAction@menu");
$replier->listen("thank you|thanks", "TalkAction@thanks");
$replier->listen("list", "TalkAction@lists");
$replier->listen("buy", "TalkAction@buy");
$replier->listen("lat: {lat} long: {lng}", "TalkAction@locations");

$replier->listen([
    "show me your photo",
    "your photo",
    "send me your photo"
], "TalkAction@mePhoto");

$replier->listen([
    "eid",
    "eid mubarak",
    "wishing eid"
], "TalkAction@eid");

$replier->listen([
    "i am from {country}",
    "from {country}"
], "TalkAction@country");


$replier->listen([
    "i am {name} from {country}",
    "{name} from {country}"
], "TalkAction@identity");

$replier->listen([
    "i born in {year}",
    "i born {year}",
    "born in {year}",
    "born {year}"
], "TalkAction@born");
