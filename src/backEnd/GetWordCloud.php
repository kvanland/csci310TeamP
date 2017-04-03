<?php

include "WordCloud.php";

$searchedWord = $_GET["searchTerm"];
$searchType = $_GET["searchType"];
$numArticles = $_GET["numArticles"];


echo WordCloudDriver::getWordCloud($searchedWord, $searchType, $numArticles);

class WordCloudDriver
{

    public static function getWordCloud($term, $type, $articleCount){
        session_start();
        $wordCloud = new WordCloud();
        $_SESSION["wordCloud"] = $wordCloud;

        $result = $wordCloud->initializeArticleList($term, $type, $articleCount);

        $sendObj = array("result"=>$result);

        return json_encode($sendObj);

    }

}