<?php

include "WordCloud.php";

session_start();
$wc= $_SESSION["wordCloud"];


echo StatusDriver::getStatus($wc);

class StatusDriver{

    public static function getStatus($wordCloud){
        $i = $wordCloud->parseNextArticle();
        return $i;
    }
}