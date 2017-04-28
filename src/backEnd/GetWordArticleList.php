<?php
/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 4/7/17
 * Time: 3:47 PM
 */

include "WordCloud.php";


session_start();
$wordCloud = $_SESSION["wordCloud"];

$word = $_GET["word"];


echo WordArticleListDriver::getWordArticleList($word, $wordCloud);

class WordArticleListDriver{

    public static function getWordArticleList($word, $wc){
        if(is_object($wc))
            return $wc->getWordListOfArticles($word);
    }

}