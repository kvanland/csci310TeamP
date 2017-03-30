<?php

/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 3/29/17
 * Time: 8:11 PM
 */
include "Constants.php";
include "Article.php";

class WordCloud
{
    private $wcData;
    private $articlesRead;
    private $articleList;

    public function wordCloud(){
        $this->wcData = array();
        $this->articleList = array();
    }

    public function initializeArticleList($searchWord, $type, $articleCount){
        $IEEEArticleList = $this->getArticleListIEEE($searchWord, $type, $articleCount);
    }

    private function getArticleListIEEE($searchWord, $type, $articleCount)
    {
        $IEEEArticleList = array();
        $url = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?";
        if ($type == "author") {
            $author = str_replace(" ", "+", $searchWord);
            $url = $url . "au=" . $author . "&hc=" . $articleCount;
        }
        
        $xml = simplexml_load_file($url);


        for($i = 0; $i <$articleCount; $i++){
            $title = (string) $xml->document[$i]->title;
            $authors = (string) $xml->document[$i]->authors;
            $conferences = (string) $xml->document[$i]->pubtitle;
            $pdfUrl = (string) $xml->document[$i]->pdf;
            $source = Constants::IEEE;
            $article = new Article($pdfUrl, $authors, $conferences, $title, $source);
            //echo "Title: ".$article->name. "\nauthors: ".$article->authors."\nconferences: ".$article->conferences."\nurl: ".$article->url. "\ndatabase: ".$article->database;
            array_push($IEEEArticleList, $article);
        }

        return $IEEEArticleList;
    }

}


$wc =new WordCloud();
$wc->initializeArticleList("Andrea Zanella", "author", 20);
