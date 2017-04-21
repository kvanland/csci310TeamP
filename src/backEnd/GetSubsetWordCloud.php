<?php
/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 4/20/17
 * Time: 4:46 PM
 */


session_start();

$wc = new WordCloud();
echo SubsetDriver::setArticleList($wc,array());



class SubsetDriver{

    public static function setArticleList($wordCloud, $articleArray){
        $wordCloud->articleList = $articleArray;
        $_SESSION["wordCloud"]= $wordCloud;

        $sendObj = array("result"=>"success");

        return json_encode($sendObj);
    }
}