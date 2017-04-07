<?php

use PHPUnit\Framework\TestCase;

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

    public function testGetArticleListIEEEAuthor(){
        $wordCloud = new WordCloud();
        $result = $wordCloud->getArticleListIEEE("Andrea Zanella","author",40);

        $nameBoolean = $result[0]->name == "On the impact of physical layer awareness on scheduling and resource allocation in broadband multicellular IEEE 802.16 systems [Radio Resource Management and Protocol Engineering for IEEE 802.16]";
        $authorBool = $result[0]->authors[0] == "Leonardo Badia";
        $urlBool = $result[0]->url == "http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=4107932";
        $conferenceBool = $result[0]->conferences == "IEEE Wireless Communications";
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
        //print_r($result);

        $nameBoolean = $result[4]->name == "Dawn";
        $authorBool = $result[4]->authors[0] == "NVIDIA Demo Team";
        $urlBool = $result[4]->url == "http://dx.doi.org/10.1145/1006032.1006034";
        $conferenceBool = $result[4]->conferences[0] == "ACM SIGGRAPH 2003 video review on Electronic theater program on Electronic theater program -";
        $databaseBool = $result[4]->database == Constants::ACM;

        $this->assertEquals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool  && (sizeof($result) == 20), true);
    }

    public function testGetArticleListIEEEKeyWord(){
        $wordCloud = new WordCloud();
        $result = $wordCloud->getArticleListIEEE("stratus","keyWord",40);

        $nameBoolean = $result[0]->name == "Cloud discrimination using K Nearest Neighbors classifier: Application to dataset generated by S&#x00E9;tif RADAR (Algeria) and MSG-SEVIRI satellite images";
        $authorBool = $result[0]->authors[0] == "Fatiha Mokdad";
        $urlBool = $result[0]->url == "http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=7489272";
        $conferenceBool = $result[0]->conferences == "2015 15th International Conference on Intelligent Systems Design and Applications (ISDA)";
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
        $wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $wordCloud->articlesRead = 20;
        $wordCloud->parseArticleACM();
        $differentBool = $wordCloud->wcData["different"] == 2;
        $inequalitiesBool = $wordCloud->wcData["inequalities"] == 1;

        $this->assertEquals($differentBool && $inequalitiesBool, true);
    }

    public function testParseIEEE(){
        $wordCloud = new WordCloud();
        $wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $wordCloud->parseArticleIEEE();
        $theyBool = $wordCloud->wcData["they"] == 2;
        $andBool = $wordCloud->wcData["and"] == 6;

        $this->assertEquals($theyBool && $andBool, true);
    }

    public function testGetWordCloudData(){
        $wordCloud = new WordCloud();
        $wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $wordCloud->parseArticleIEEE();

        json_decode($wordCloud->getWordCloudData());

        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }




}