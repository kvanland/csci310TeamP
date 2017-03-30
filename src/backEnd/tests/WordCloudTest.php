<?php

use PHPUnit\Framework\TestCase;

class WordCloudTest extends TestCase
{
    protected $wordCloud;

    protected function setUp(){
        $this->$wordCloud = new WordCloud();
    }

    public function testArticleListAuthorACM(){
        $this->wordCloud->initializeArticleList("Andrea Zanella","author",40);

        $nameBoolean = $this->wordCloud->articleList[0]->name == "Queueing Theory";
        $authorBool = $this->wordCloud->articleList[0]->authors[0] == "Andrea Zanella";
        $urlBool = $this->wordCloud->articleList[0]->url == "http:\/\/dx.doi.org\/10.1002\/9781119978589.ch8";
        $conferenceBool = $this->wordCloud->articleList[0]->conferences[0] == "Principles of Communications Networks and Systems";
        $databaseBool = $this->wordCloud->articleList[0]->source == Constants::ACM;

        $this->assert_equals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool && (sizeof($this->wordCloud->articleList) == 40), true);

    }

    public function testArticleListAuthorIEEE(){
        $this->wordCloud->initializeArticleList("Andrea Zanella","keyWord",40);

        $nameBoolean = $this->wordCloud->articleList[20]->name == "On the impact of physical layer awareness on scheduling and resource allocation in broadband multicellular IEEE 802.16 systems [Radio Resource Management and Protocol Engineering for IEEE 802.16]";
        $authorBool = $this->wordCloud->articleList[20]->authors[0] == "Leonardo Badia";
        $urlBool = $this->wordCloud->articleList[20]->url == "http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=4444656";
        $conferenceBool = $this->wordCloud->articleList[20]->conferences[0] == "IEEE Wireless Communications";
        $databaseBool = $this->wordCloud->articleList[0]->source == Constants::IEEE;

        $this->assert_equals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool && (sizeof($this->wordCloud->articleList) == 40), true);
    }


    public function testArticleListKeyWordACM(){
        $this->wordCloud->initializeArticleList("stratus","keyWord",40);

        $nameBoolean = $this->wordCloud->articleList[0]->name == "STRATUS";
        $authorBool = $this->wordCloud->articleList[0]->authors[0] == "Suzanne Kieffe";
        $urlBool = $this->wordCloud->articleList[0]->url == "http:\/\/dx.doi.org\/10.1145\/2851613.2851912";
        $conferenceBool = $this->wordCloud->articleList[0]->conferences[0] == "Proceedings of the 31st Annual ACM Symposium on Applied Computing - SAC '16";
        $databaseBool = $this->wordCloud->articleList[0]->source == Constants::ACM;

        $this->assert_equals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool  && (sizeof($this->wordCloud->articleList) == 40), true);
    }

    public function testArticleListKeyWordIEEE(){
        $this->wordCloud->initializeArticleList("stratus","keyWord",40);

        $nameBoolean = $this->wordCloud->articleList[3]->name == "Cloud discrimination using K Nearest Neighbors classifier: Application to dataset generated by S&#x00E9;tif RADAR (Algeria) and MSG-SEVIRI satellite images";
        $authorBool = $this->wordCloud->articleList[3]->authors[0] == "Fatiha Mokdad";
        $urlBool = $this->wordCloud->articleList[3]->url == "http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=7489272";
        $conferenceBool = $this->wordCloud->articleList[3]->conferences[0] == "2015 15th International Conference on Intelligent Systems Design and Applications (ISDA)
]]>";
        $databaseBool = $this->wordCloud->articleList[0]->source == Constants::IEEE;

        $this->assert_equals($nameBoolean && $authorBool && $urlBool && $conferenceBool && $databaseBool && (sizeof($this->wordCloud->articleList) == 40), true);
    }

    public function testParseIEEE(){
        $this->wordCloud->initializeArticleList("Andrea Zanella","keyWord",40);

        $this->wordCloud->parseArticleIEEE($this->wordCloud->articleList[20]->)

    }


}