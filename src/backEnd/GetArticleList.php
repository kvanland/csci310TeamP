<?php
/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 4/7/17
 * Time: 3:47 PM
 */

include "WordCloud.php";

$word = $_GET["word"];


echo ArticleListDriver::getArticleList($word);

class ArticleListDriver{

    public static function getArticleList($word){
        session_start();
        $wordCloud = $_SESSION["wordCloud"];
        $i = $wordCloud->getListOfArticles($word);
        return $i;
    }

}