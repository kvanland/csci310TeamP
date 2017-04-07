<?php


include "Constants.php";
include "Article.php";
include "simple_html_dom.php";
include "Word.php";

class WordCloud
{
    public $wcData;
    public $articlesRead;
    public $articleList;
    public $stopwords;


    public function __construct(){
        $this->wcData = array();
        $this->articleList = array();
        $this->articlesRead = 0;
        $this->test = 0;
        $this->stopwords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the");
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
                    if(array_key_exists("name", $author)){
                        $name = $author["name"];
                    }else{
                        $name = $author["given"];
                        $name = "$name ".$author["family"];
                    }
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

    public function parseArticleIEEE()
    {
        $apiCall = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?an=".$this->articleList[$this->articlesRead]->articleNumber;
        $xml = simplexml_load_file($apiCall);
        $abstract = $xml->document->abstract;
        $this->documentToWordCloudData($abstract, $this->articleList[$this->articlesRead]->name);

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
            $this->documentToWordCloudData($abstract, $this->articleList[$this->articlesRead]->name);
        }

    }

    public function documentToWordCloudData($string, $articleName){
        $string = strtolower($string);
        $words = explode(" ", $string);
        for ($i=0; $i < sizeof($words); $i++) { 
            if(!in_array($words[$i], $this->stopwords)) {
                if (array_key_exists($words[$i], $this->wcData)){
                    $this->wcData[$words[$i]]->wordSeen();

                } else {
                    $this->wcData[$words[$i]] = new Word($words[$i]);

                }
                $this->wcData[$words[$i]]->addArticle($articleName);
            }
        }
    }

    public function getWordCloudData(){
        // usort($this->wcData, function($a, $b) {
        //     return $a->occurrences < $b->occurrences;
        // });
        $keys = array_keys($this->wcData);

        $sendObj = array();
        for($i = 0; $i <250; $i++){
            if ($i >= count($keys))
                break;
            array_push($sendObj, array("text"=>$keys[$i], "size"=>(string)$this->wcData[$keys[$i]]->occurrences));
        }
        $json = array("status"=>100,"wordCloud"=>$sendObj);
        return json_encode($json);
    }


}
