<?php
/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 4/7/17
 * Time: 3:47 PM
 */

include "WordCloud.php";

$word = $_GET["word"];


echo WordArticleListDriver::getWordArticleList($word);

class WordArticleListDriver{

    public static function getWordArticleList($word){
        session_start();
        $wordCloud = $_SESSION["wordCloud"];
        $i = $wordCloud->getWordListOfArticles($word);
        return $i;
    }

}