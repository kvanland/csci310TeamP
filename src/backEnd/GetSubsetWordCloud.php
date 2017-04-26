<?php
/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 4/20/17
 * Time: 4:46 PM
 */
include "WordCloud.php";

$articleJSON = $_GET["articles"];

session_start();

$wc = new WordCloud();
echo SubsetDriver::setArticleList($wc,$articleJSON);



class SubsetDriver{

    public static function setArticleList($wordCloud, $articleArray){
        $articleList = array();
        foreach ($articleArray as $article){
            $articleObj = new Article($article["download"], $article["authors"], $article["conference"], $article["title"], (int)$article["database"], $article["id"]);
            $articleList[$articleObj->name] = $articleObj;
        }

        $wordCloud->articleList = $articleList;
        $_SESSION["wordCloud"]= $wordCloud;

        $sendObj = array("result"=>"success");

        return json_encode($sendObj);
    }
}