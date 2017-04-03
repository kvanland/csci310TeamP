<?php

include "WordCloud.php";


echo StatusDriver::getStatus();


class StatusDriver
{
    public static function getStatus(){
        session_start();
        $wordCloud = $_SESSION["wordCloud"];
        $i = $wordCloud->parseNextArticle();
        return $i;
    }

}