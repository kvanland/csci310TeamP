<?php


include "Constants.php";
include "Article.php";
include "simple_html_dom.php";

class WordCloud
{
    public $wcData;
    public $articlesRead;
    public $articleList;

    public function __construct(){
        $this->wcData = array();
        $this->articleList = array();
        $this->articlesRead = 0;
        $this->test = 0;
    }

    public function initializeArticleList($searchWord, $type, $articleCount){
        $this->articleList = array();
        $IEEEArticleList = $this->getArticleListIEEE($searchWord, $type, $articleCount);
        $acmArticleList = $this->getArticleListACM($searchWord, $type, $articleCount);

        $databaseCount = 0;
        $total = 0;
        if(sizeof($acmArticleList) < $articleCount/2){
            while($total < $articleCount/2 && $databaseCount < sizeof($acmArticleList)) {
                array_push($this->articleList, $acmArticleList[$databaseCount]);
                $databaseCount++;
                $total++;
            }
            $databaseCount = 0;

            while($total < $articleCount && $databaseCount < sizeof($IEEEArticleList)) {
                array_push($this->articleList, $IEEEArticleList[$databaseCount]);
                $databaseCount ++;
                $total++;
            }
        }else{
            while($total < $articleCount/2 && $databaseCount < sizeof($IEEEArticleList)) {
                array_push($this->articleList, $IEEEArticleList[$databaseCount]);
                $databaseCount ++;
                $total++;
            }
            $databaseCount = 0;

            while($total < $articleCount && $databaseCount < sizeof($acmArticleList)) {
                array_push($this->articleList, $acmArticleList[$databaseCount]);
                $databaseCount++;
                $total++;
            }
        }
        if(empty($this->articleList))
            return "fail";
        return "success";
    }

    public function getArticleListIEEE($searchWord, $type, $articleCount)
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
            $number = (string) $xml->document[$i]->arnumber;
            $article = new Article($pdfUrl, $authors, $conferences, $title, $source, $number);
            array_push($IEEEArticleList, $article);
            $i++;
        }
        return $IEEEArticleList;
    }


    public function getArticleListACM($searchWord, $type, $articleCount)
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
            $authors = array();
            $articleUrl = "";
            $title = "";
            $conferences = array();

            if(array_key_exists("title", $acmArray["message"]["items"][$i]))
                if(!empty($acmArray["message"]["items"][$i]["title"]))
                    $title = $acmArray["message"]["items"][$i]["title"][0];



            if(array_key_exists("author", $acmArray["message"]["items"][$i])){
                foreach($acmArray["message"]["items"][$i]["author"] as $author){
                    $name = $author["given"];
                    $name = "$name ".$author["family"];
                    array_push($authors, $name);
                }
            }
            if(array_key_exists("container-title", $acmArray["message"]["items"][$i]))
                if(!empty( $acmArray["message"]["items"][$i]["container-title"]))
                    $conferences = $acmArray["message"]["items"][$i]["container-title"];

            if(array_key_exists("URL", $acmArray["message"]["items"][$i]))
                $articleUrl = $acmArray["message"]["items"][$i]["URL"];
            $source = Constants::ACM;
            $article = new Article($articleUrl, $authors, $conferences, $title, $source, 0);
            array_push($acmArticleList, $article);
            $i++;
        }

        return $acmArticleList;
    }

    public function parseNextArticle()
    {
       if($this->articleList[$this->articlesRead]->database == Constants::ACM) {
           $this->parseArticleACM();
       }
       else {
           $this->parseArticleIEEE();

       }
       $this->articlesRead++;
       if($this->articlesRead == sizeof($this->articleList))
           return $this->getWordCloudData();
       $json = array("status"=>((double)($this->articlesRead)/sizeof($this->articleList))*100, "wordCloud"=>"");
       return json_encode($json);
    }

    private function parseArticleIEEE()
    {
        $apiCall = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?an=".$this->articleList[$this->articlesRead]->articleNumber;
        $xml = simplexml_load_file($apiCall);
        $abstract = $xml->document->abstract;
        $this->documentToWordCloudData($abstract);

    }

    public function parseArticleACM()
    {


        $url = str_replace("\\", "", $this->articleList[$this->articlesRead]->url);
        $cr = curl_init($url);
        curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cr, CURLOPT_FOLLOWLOCATION, 1);
        curl_exec($cr);
        $info = curl_getinfo($cr);
        $url = $info["url"];
        $url = $url."&preflayout=flat";
        $html = file_get_html($url);


        if(empty($html))
            return;

        $abstractNotAvailable = empty($html->find("A[NAME=abstract]", 0)->parent()->next_sibling()->find("p", 0));
        if (!$abstractNotAvailable){
            $abstract = $html->find("A[NAME=abstract]", 0)->parent()->next_sibling()->find("p", 0)->innertext();
            $this->documentToWordCloudData($abstract);
        }

    }

    public function documentToWordCloudData($string){
        $string = strtolower($string);
        $words = explode(" ", $string);
        for ($i=0; $i < sizeof($words); $i++) { 
            if (array_key_exists($words[$i], $this->wcData)){
                $this->wcData[$words[$i]]+=1;
            } else {
                $this->wcData[$words[$i]] = 1;
            }
        }
    }

    public function getWordCloudData(){
        arsort($this->wcData);
        $keys = array_keys($this->wcData);

        $sendObj = array();
        for($i = 0; $i <250; $i++){
            array_push($sendObj, array("text"=>$keys[$i], "size"=>(string)$this->wcData[$keys[$i]]));
        }
        $json = array("status"=>100,"wordCloud"=>$sendObj);
        return json_encode($json);
    }


}
