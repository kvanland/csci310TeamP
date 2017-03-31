<?php

include "WordCloud.php";


StatusDriver::getStatus();


class StatusDriver
{
    public static function getStatus(){
        session_start();
        $wordCloud = $_SESSION["wordCloud"];
        $i = $wordCloud->parseNextArticle();
        echo $i;
    }

}