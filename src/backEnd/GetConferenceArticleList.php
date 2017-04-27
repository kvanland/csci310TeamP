<?php
/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 4/11/17
 * Time: 5:02 PM
 */

include "Constants.php";
include "Article.php";
include "simple_html_dom.php";
include "PDFParser.php";
include "../vendor/autoload.php";


ConferenceArticleListDriver::$conference = $_GET["conference"];
ConferenceArticleListDriver::$word = $_GET["word"];
ConferenceArticleListDriver::$listSize = $_GET["numArticles"];
ConferenceArticleListDriver::$numArticles = 4 * ConferenceArticleListDriver::$listSize;
ConferenceArticleListDriver::$articles = array();
ConferenceArticleListDriver::$articleList = array();



echo ConferenceArticleListDriver::getConferenceArticleList();


class ConferenceArticleListDriver{

    public static $articles;
    public static $numArticles;
    public static $listSize;
    public static $word;
    public static $conference;
    public static $articleList;

    public static function getConferenceArticleList(){
        ConferenceArticleListDriver::getIEEE(ConferenceArticleListDriver::$conference);
        ConferenceArticleListDriver::getACM(ConferenceArticleListDriver::$conference);

        foreach (ConferenceArticleListDriver::$articles as $article){
            if(sizeof(ConferenceArticleListDriver::$articleList) >= ConferenceArticleListDriver::$listSize)
                break;
            if($article->database == Constants::ACM)
                ConferenceArticleListDriver::parseACM($article);
            else
                ConferenceArticleListDriver::parseIEEE($article);


        }


        return json_encode(ConferenceArticleListDriver::$articleList);
    }

    public static function getIEEE()
    {
        $url = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?jn=".ConferenceArticleListDriver::$conference."&hc=100";

        $xml = simplexml_load_file($url);
        if (empty($xml))
            return;

        $i = 0;
        while(sizeof(ConferenceArticleListDriver::$articles)<ConferenceArticleListDriver::$numArticles && $i < sizeof($xml->document)){
            if(strcmp(ConferenceArticleListDriver::$conference, $xml->document[$i]->pubtitle) == 0) {
                $title = (string)$xml->document[$i]->title;
                $authors = explode("; ", (string)$xml->document[$i]->authors);
                $conference = (string)$xml->document[$i]->pubtitle;
                $pdfUrl = (string)$xml->document[$i]->pdf;
                $source = Constants::IEEE;
                $number = (string)$xml->document[$i]->arnumber;
                $article = new Article($pdfUrl, $authors, $conference, $title, $source, $number);
                array_push(ConferenceArticleListDriver::$articles, $article);
            }
            $i++;
        }
    }

    public static function getACM(){
        $urlConference = str_replace(" ", "+", ConferenceArticleListDriver::$conference);
        $url = "http://api.crossref.org/works?filter=member:320&query=$urlConference&sort=score&order=desc&rows=100";
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
        while(sizeof(ConferenceArticleListDriver::$articles) < ConferenceArticleListDriver::$numArticles && $i < sizeof($acmArray["message"]["items"])){
            $authors = array();
            $articleUrl = "";
            $title = "";
            
            if(!array_key_exists("container-title", $acmArray["message"]["items"][$i])) {
                $i++;
                continue;
            }

            if(empty($acmArray["message"]["items"][$i]["container-title"])) {
                $i++;
                continue;
            }

            if(strcmp(ConferenceArticleListDriver::$conference, $acmArray["message"]["items"][$i]["container-title"][0]) == 0) {
                if (array_key_exists("title", $acmArray["message"]["items"][$i]))
                    if (!empty($acmArray["message"]["items"][$i]["title"]))
                        $title = $acmArray["message"]["items"][$i]["title"][0];


                if (array_key_exists("author", $acmArray["message"]["items"][$i])) {
                    foreach ($acmArray["message"]["items"][$i]["author"] as $author) {
                        if (array_key_exists("name", $author)) {
                            $name = $author["name"];
                        } else {
                            $name = "";
                            if (array_key_exists("given", $author))
                                $name = $author["given"];
                            if (array_key_exists("family", $author))
                                $name = "$name " . $author["family"];
                        }
                        array_push($authors, $name);
                    }
                }
                $conference = $acmArray["message"]["items"][$i]["container-title"][0];

                if (array_key_exists("URL", $acmArray["message"]["items"][$i]))
                    $articleUrl = $acmArray["message"]["items"][$i]["URL"];
                $source = Constants::ACM;
                $articleNumber = $articleUrl;
                $article = new Article($articleUrl, $authors, $conference, $title, $source, $articleNumber);
                array_push(ConferenceArticleListDriver::$articles, $article);
                $i++;
            }
            else
                $i++;
        }
    }

    public static function parseACM($article){
        $url = str_replace("\\", "", $article->url);
        error_log($url);
        $cr = curl_init($url);
        curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cr, CURLOPT_FOLLOWLOCATION, 1);
        curl_exec($cr);
        $info = curl_getinfo($cr);
        $url = $info["url"];
        $url = $url."&preflayout=flat";
        error_log($url);
        shell_exec("python getPDF.py '".$url."' a");
        $pdfParser = new PDFParser();
        $pdf_text = $pdfParser->parsePDF('currentPDF.pdf');
        if (!$pdf_text) {
            return;
        }

        $count = ConferenceArticleListDriver::countWordOccurence($pdf_text);

        if($count != 0) {
            $array = array("title" => $article->name, "authors" => $article->authors,
                "conference" => $article->conferences, "download" => $article->url, "bibtex" => "http://dl.acm.org/exportformats.cfm?id=" . $article->articleNumber . "&expformat=bibtex", "id" => $article->articleNumber,
                "database" => $article->database, "frequency" => (string)$count);
            array_push(ConferenceArticleListDriver::$articleList, $array);
        }



    }

    public static function parseIEEE($article){
        $apiCall = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?an=".$article->articleNumber;
        $xml = simplexml_load_file($apiCall);
        $pdf_link = $xml->document->pdf;
        error_log($pdf_link);
        shell_exec("python getPDF.py '".$pdf_link."' i");
        $pdfParser = new PDFParser();
        $pdf_text = $pdfParser->parsePDF('currentPDF.pdf');
        if (!$pdf_text) {
            return;
        }

        $count = ConferenceArticleListDriver::countWordOccurence($pdf_text);

        if ($count != 0) {
            array_push(ConferenceArticleListDriver::$articleList, array("title" => $article->name, "authors" => $article->authors, "frequency" => $count,
                "conference" => $article->conferences, "download" => $article->url, "bibtex" => "http://ieeexplore.ieee.org/xpl/downloadCitations?recordIds=" . $article->articleNumber . "&citations-format=citation-only&download-format=download-bibtex", "id" => $article->articleNumber,
                "database" => $article->database));
        }
    }

    public static function countWordOccurence($abstract){
        $string = strtolower($abstract);
        $words = explode(" ", $string);
        $count = 0;
        foreach ($words as $word){
            if(strcmp($word, ConferenceArticleListDriver::$word) == 0)
                $count++;
        }
        return $count;
    }



}
