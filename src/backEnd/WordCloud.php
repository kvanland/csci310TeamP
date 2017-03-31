<?php

/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 3/29/17
 * Time: 8:11 PM
 */
include "Constants.php";
include "Article.php";
include_once('simple_html_dom.php');

class WordCloud
{
    private $wcData;
    private $articlesRead;
    private $articleList;

    public function __construct(){
        $this->wcData = array();
        $this->articleList = array();
    }

    public function initializeArticleList($searchWord, $type, $articleCount){
        $this->articleList = array();
        $IEEEArticleList = $this->getArticleListIEEE($searchWord, $type, $articleCount);
        $acmArticleList = $this->getArticleListACM($searchWord, $type, $articleCount);
        $i = 0;
        while($i < $articleCount/2 && $i < sizeof($IEEEArticleList)) {
            array_push($this->articleList, $IEEEArticleList[$i]);
            $i ++;
        }

        $i = 0;
        while($i < $articleCount/2 && $i < sizeof($acmArticleList)) {
            array_push($this->articleList, $acmArticleList[$i]);
            $i++;
        }

        print_r($this->articleList);
    }

    private function getArticleListIEEE($searchWord, $type, $articleCount)
    {
        $IEEEArticleList = array();
        $url = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?";
        if ($type == "author") {
            $author = str_replace(" ", "+", $searchWord);
            $url = $url . "au=$author";
        }else{
            $keyword = str_replace(" ", "+", $searchWord);
            $url = $url . "querytext=$keyword";
        }
        $url = $url. "&hc=$articleCount";

        $xml = simplexml_load_file($url);

       if(empty($xml))
           return $IEEEArticleList;

        $i = 0;
        while($i<$articleCount && $i < sizeof($xml->document)){
            $title = (string) $xml->document[$i]->title;
            $authors = explode("; ",(string) $xml->document[$i]->authors);
            $conferences = (string) $xml->document[$i]->pubtitle;
            $pdfUrl = (string) $xml->document[$i]->pdf;
            $source = Constants::IEEE;
            $article = new Article($pdfUrl, $authors, $conferences, $title, $source);
            array_push($IEEEArticleList, $article);
            $i++;
        }
        return $IEEEArticleList;
    }


    private function getArticleListACM($searchWord, $type, $articleCount)
    {
        $acmArticleList = array();
        $url = "http://api.crossref.org/works?filter=member:320&";
        if ($type == "author") {
            $author = str_replace(" ", "+", $searchWord);
            $url = $url ."query.author=$author";
        }else{
            $keyword = str_replace(" ", "+", $searchWord);
            $url = $url . "query=$keyword";
        }

        $url = $url. "&sort=score&order=desc&rows=$articleCount";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $json = curl_exec($ch);
        curl_close($ch);

        $acmArray = json_decode($json, true);



        $i = 0;
        while($i < $articleCount && $i < sizeof($acmArray["message"]["items"])){
            $title = $acmArray["message"]["items"][$i]["title"][0];
            $authors = array();
            foreach($acmArray["message"]["items"][$i]["author"] as $author){
                $name = $author["given"];
                $name = "$name ".$author["family"];
                array_push($authors, $name);
            }
            $conferences = $acmArray["message"]["items"][$i]["container-title"][0];
            $articleUrl = $acmArray["message"]["items"][$i]["URL"];
            $source = Constants::ACM;
            $article = new Article($articleUrl, $authors, $conferences, $title, $source);
            array_push($acmArticleList, $article);
            $i++;
        }
        return $acmArticleList;
    }

    public function parseNextArticle()
    {
        $author = array("Michael Losavio");
        $this->parseArticleIEEE("Digital heritage from the Smart City and the Internet of Things", $author);
        // $this->parseArticleACM();
    }

    private function parseArticleIEEE($title, $author)
    {
        $authorStr = $author[0];
        for ($i=1; $i < sizeof($author); $i++) { 
            $authorStr = $authorStr."+".$author[i];
        }
        $apiCall = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?ti=".$title."?au=".$authorStr;

        // create curl resource 
        $ch = curl_init(); 
        // set url 
        curl_setopt($ch, CURLOPT_URL, $apiCall); 
        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        // $output contains the output string 
        $out = curl_exec($ch); 
        // close curl resource to free up system resources 
        curl_close($ch); 

        $xml=simplexml_load_string($out) or die("Error: Cannot create object");
        $abstract = $xml->document->abstract;
        $this->documentToWordCloudData($abstract);

    }

    private function parseArticleACM()
    {
        $flatLayoutStr = "&preflayout=flat";
        $url = str_replace("\\", "", $articleList[$articlesRead]->url);
        $url = "http://dl.acm.org/citation.cfm?doid=3007120.3007131"."&preflayout=flat";
        $html = file_get_html($url);

        $abstract = $html->find("A[NAME=abstract]",0)->parent()->next_sibling()->find("p",0)->innertext();
        $this->documentToWordCloudData($abstract);
    }

    private function documentToWordCloudData($string){
        $string = strtolower($string);
        $words = explode(" ", $string);
        for ($i=0; $i < sizeof($words); $i++) { 
            if (array_key_exists($words[$i], $wcData)){
                $wcData[$words[$i]]+=1;
            } else {
                $wcData[$words[$i]] = 1;
            }
        }
        echo print_r($wcData);
    }


}
