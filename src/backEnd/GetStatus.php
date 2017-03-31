<?php



class StatusDriver
{
    public static function getStatus(){
        session_start();
        $wordCloud = $_SESSION["wordCloud"];
        return $wordCloud->parseNextArticle();
    }

}