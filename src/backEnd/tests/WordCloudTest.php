<?php

use PHPUnit\Framework\TestCase;
include "ArticleTest.php";

class WordCloudTest extends TestCase
{

    public function testInitializeArticleListWithArticlesFound(){
        $wordCloud = new WordCloud();
        $result = $wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $this->assertEquals($result, "success");
    }

    public function testInitializeArticleListGetsCorrectAmount(){
        $wordCloud = new WordCloud();
        $result = $wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $this->assertEquals(sizeof($wordCloud->articleList), 40);
    }

    public function testInitializeArticleListWithArticlesNotFound(){
        $wordCloud = new WordCloud();
        $result = $wordCloud->initializeArticleList("adsfadsf","author",40);
        $this->assertEquals($result, "fail");
    }

    public function testGetArticleListACMAuthor(){
        $wordCloud = new WordCloud();
        $result = $wordCloud->getArticleListACM("Andrea Zanella","author",40);

        $nameBoolean = $result[0]->name == "Analysis of opportunistic localization algorithms based on the linear matrix inequality method";
        $authorBool = $result[0]->authors[0] == "Francesco Zorzi";
        $urlBool = $result[0]->url == "http://dx.doi.org/10.1145/1755743.1755777";
        $conferenceBool = $result[0]->conferences[0] == "Proceedings of the Second International Workshop on Mobile Opportunistic Networking - MobiOpp '10";
        $databaseBool = $result[0]->database == Constants::ACM;

        $this->assertEquals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool && (sizeof($result) == 40), true);

    }

    public function testGetArticleListACMAuthorNoGiven(){
        $wordCloud = new WordCloud();
        $result = $wordCloud->getArticleListACM("Mu Wang","author",5);

        //works if test doesn't crash
        $this->assertEquals(true, true);

    }

    public function testGetArticleListIEEEAuthor(){
        $wordCloud = new WordCloud();
        $result = $wordCloud->getArticleListIEEE("Andrea Zanella","author",40);

        $nameBoolean = $result[0]->name == "Throughput and Energy Efficiency of Bluetooth v2 + EDR in Fading Channels";
        $authorBool = $result[0]->authors[0] == "Andrea Zanella";
        $urlBool = $result[0]->url == "http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=4489328";
        $conferenceBool = $result[0]->conferences == "2008 IEEE Wireless Communications and Networking Conference";
        $databaseBool = $result[0]->database == Constants::IEEE;

        $this->assertEquals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool && (sizeof($result) == 40), true);
    }


    public function testGetArticleListACMKeyWord(){
        $wordCloud = new WordCloud();
        $result = $wordCloud->getArticleListACM("stratus","keyWord",40);

        $nameBoolean = $result[0]->name == "STRATUS";
        $authorBool = $result[0]->authors[0] == "Suzanne Kieffer";
        $urlBool = $result[0]->url == "http://dx.doi.org/10.1145/2851613.2851912";
        $conferenceBool = $result[0]->conferences[0] == "Proceedings of the 31st Annual ACM Symposium on Applied Computing - SAC '16";
        $databaseBool = $result[0]->database == Constants::ACM;

        $this->assertEquals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool  && (sizeof($result) == 3), true);
    }

    public function testGetArticleListACMNameInAuthorBug(){
        $wordCloud = new WordCloud();
        $result = $wordCloud->getArticleListACM("program","keyWord",20);

        $bool = false;
       foreach ($result as $article){
           if($article->name = "Dawn")
               if($article->authors[0] = " NVIDIA Demo Team")
               $bool = true;
       }

        $this->assertEquals($bool, true);
    }

    public function testGetArticleListIEEEKeyWord(){
        $wordCloud = new WordCloud();
        $result = $wordCloud->getArticleListIEEE("stratus","keyWord",40);

        $nameBoolean = $result[0]->name == "Physical modeling of em wave propagation over the earth";
        $authorBool = $result[0]->authors[0] == "R. J. King";
        $urlBool = $result[0]->url == "http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=7767948";
        $conferenceBool = $result[0]->conferences == "Radio Science";
        $databaseBool = $result[0]->database == Constants::IEEE;

        $this->assertEquals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool && (sizeof($result) == 40), true);
    }

    public function testParseNextArticleWhenThereAreArticlesLeft(){
        $wordCloud = new WordCloud();
        $wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $json = $wordCloud->parseNextArticle();

        $this->assertEquals(json_last_error(), JSON_ERROR_NONE);
    }

    public function testParseNextArticleOnLastArticleRead(){
        $wordCloud = new WordCloud();
        $wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $wordCloud->articlesRead = 39;
        $json = ($wordCloud->parseNextArticle());

        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }


    public function testParseACM(){
        $wordCloud = new WordCloud();

        $article = new article("http:\/\/dx.doi.org\/10.1145\/1755743.1755777", "", "", "Analysis of opportunistic localization algorithms based on the linear matrix inequality method", 0, "http:\/\/dx.doi.org\/10.1145\/1755743.1755777");
        $wordCloud->parseArticleACM($article);

        $modelBool = $wordCloud->wcData["model"]->occurrences == 4;
        $systemsBool = $wordCloud->wcData["systems"]->occurrences == 2;

        $this->assertEquals($modelBool && $systemsBool, true);
    }

    public function testParseIEEE(){
        $wordCloud = new WordCloud();

        $article = new article("http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=4489328", "", "", "Throughput and Energy Efficiency of Bluetooth v2 + EDR in Fading Channels", 0, "4489328");
        $wordCloud->parseArticleIEEE($article);

        $frameworkBool = $wordCloud->wcData["framework"]->occurrences == 7;
        $consumptionBool = $wordCloud->wcData["consumption"]->occurrences == 1;

        $this->assertEquals($frameworkBool && $consumptionBool, true);
    }

    public function testGetWordCloudData(){
        $wordCloud = new WordCloud();

        $article = new article("http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=4489328", "", "", "Throughput and Energy Efficiency of Bluetooth v2 + EDR in Fading Channels", 0, "4489328");
        $wordCloud->parseArticleIEEE($article);

        json_decode($wordCloud->getWordCloudData());

        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }

    public function testGetWordListOfArticles(){
        $wordCloud = new WordCloud();
        $wordCloud->initializeArticleList("Andrea Zanella","author",10);

        for($i = 0; $i < 10; $i++){
            $wordCloud->parseNextArticle();
        }

        json_decode($wordCloud->getWordListOfArticles("computer"));
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }





}