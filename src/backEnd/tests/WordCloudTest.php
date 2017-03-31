<?php

use PHPUnit\Framework\TestCase;

class WordCloudTest extends TestCase
{
    protected $wordCloud;

    protected function setUp(){
        $this->wordCloud = new WordCloud();
    }

    public function testInitializeArticleListWithArticlesFound(){
        $result = $this->wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $this->assertEquals($result, "success");
    }

    public function testInitializeArticleListWithArticlesNotFound(){
        $result = $this->wordCloud->initializeArticleList("adsfadsf","author",40);
        $this->assertEquals($result, "fail");
    }

    public function testGetArticleListACMAuthor(){
        $this->wordCloud->getArticleListACM("Andrea Zanella","author",40);

        $nameBoolean = $this->wordCloud->articleList[0]->name == "Analysis of opportunistic localization algorithms based on the linear matrix inequality method";
        $authorBool = $this->wordCloud->articleList[0]->authors[0] == "Francesco Zorzi";
        $urlBool = $this->wordCloud->articleList[0]->url == "http:\/\/dx.doi.org\/10.1145\/1755743.1755777";
        $conferenceBool = $this->wordCloud->articleList[0]->conferences[0] == "Proceedings of the Second International Workshop on Mobile Opportunistic Networking - MobiOpp '10";
        $databaseBool = $this->wordCloud->articleList[0]->source == Constants::ACM;

        $this->assetEquals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool && (sizeof($this->wordCloud->articleList) == 40), true);

    }

    public function testGetArticleListIEEEAuthor(){
        $this->wordCloud->getArticleListIEEE("Andrea Zanella","author",40);

        $nameBoolean = $this->wordCloud->articleList[0]->name == "On the impact of physical layer awareness on scheduling and resource allocation in broadband multicellular IEEE 802.16 systems [Radio Resource Management and Protocol Engineering for IEEE 802.16]";
        $authorBool = $this->wordCloud->articleList[0]->authors[0] == "Leonardo Badia";
        $urlBool = $this->wordCloud->articleList[0]->url == "http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=4444656";
        $conferenceBool = $this->wordCloud->articleList[0]->conferences[0] == "IEEE Wireless Communications";
        $databaseBool = $this->wordCloud->articleList[0]->source == Constants::IEEE;

        $this->assetEquals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool && (sizeof($this->wordCloud->articleList) == 40), true);
    }


    public function testGetArticleListACMKeyWord(){
        $this->wordCloud->getArticleListACM("stratus","keyWord",40);

        $nameBoolean = $this->wordCloud->articleList[0]->name == "STRATUS";
        $authorBool = $this->wordCloud->articleList[0]->authors[0] == "Suzanne Kieffe";
        $urlBool = $this->wordCloud->articleList[0]->url == "http:\/\/dx.doi.org\/10.1145\/2851613.2851912";
        $conferenceBool = $this->wordCloud->articleList[0]->conferences[0] == "Proceedings of the 31st Annual ACM Symposium on Applied Computing - SAC '16";
        $databaseBool = $this->wordCloud->articleList[0]->source == Constants::ACM;

        $this->assetEquals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool  && (sizeof($this->wordCloud->articleList) == 3), true);
    }

    public function testGetArticleListIEEEKeyWord(){
        $this->wordCloud->getArticleListIEEE("stratus","keyWord",40);

        $nameBoolean = $this->wordCloud->articleList[0]->name == "Cloud discrimination using K Nearest Neighbors classifier: Application to dataset generated by S&#x00E9;tif RADAR (Algeria) and MSG-SEVIRI satellite images";
        $authorBool = $this->wordCloud->articleList[0]->authors[0] == "Fatiha Mokdad";
        $urlBool = $this->wordCloud->articleList[0]->url == "http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=7489272";
        $conferenceBool = $this->wordCloud->articleList[0]->conferences[0] == "2015 15th International Conference on Intelligent Systems Design and Applications (ISDA)
]]>";
        $databaseBool = $this->wordCloud->articleList[0]->source == Constants::IEEE;

        $this->assetEquals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool && (sizeof($this->wordCloud->articleList) == 40), true);
    }

    public function testParseNextArticleWhenThereAreArticlesLeft(){
        $this->wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $this->wordCloud->wcData = array();
        $this->wordCloud->articlesRead = 0;
        $json = $this->wordCloud->parseNextArticle();

        $this->assertNull($json);
    }

    public function testParseNextArticleWhenNoArticlesAreLeft(){
        $this->wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $this->wordCloud->wcData = array();
        $this->wordCloud->articlesRead = 40;
        json_decode($this->wordCloud->parseNextArticle());

        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }


    public function testParseACM(){
        $this->wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $this->wordCloud->wcData = array();
        $this->wordCloud->parseArticleACM($this->wordCloud->articleList[0]->url);
        $studyBool = $this->wordCloud->wcData["study"]->occurrences == 3;
        $linearBool = $this->wordCloud->wcData["linear"]->occurrences == 6;

        $this->assetEquals($studyBool && $linearBool, true);
    }

    public function testParseIEEE(){
        $this->wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $this->wordCloud->wcData = array();
        $this->wordCloud->parseArticleIEEE($this->wordCloud->articleList[20]->url);
        $timeSlotBool = $this->wordCloud->wcData["timeslot"]->occurrences == 1;
        $tinyOsBool = $this->wordCloud->wcData["TinyOS"]->occurences == 7;

        $this->assetEquals($timeSlotBool && $tinyOsBool, true);
    }

    public function testGetWordCloudData(){
        $this->wordCloud->initializeArticleList("Andrea Zanella","author",40);
        $this->wordCloud->wcData = array();
        $this->wordCloud->parseArticleIEEE($this->wordCloud->articleList[20]->url);

        json_decode($this->wordCloud->getWordCloudData());

        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }




}