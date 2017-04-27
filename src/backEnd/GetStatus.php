<?php

include "WordCloud.php";

session_start();
$wc = $_SESSION["wordCloud"];

echo StatusDriver::getStatus($wc);

class StatusDriver{

    public static function getStatus($wordCloud){
        if(is_object($wordCloud))
            return $wordCloud->parseNextArticle();
    }
}