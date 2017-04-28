<?php



use PHPUnit\Framework\TestCase;

class GetConferenceArticleListTest extends TestCase
{

    public function testGetConferenceArticleListValidJson()
    {
        ConferenceArticleListDriver::$articleList =array();
        ConferenceArticleListDriver::$articles = array();
        ConferenceArticleListDriver::$numArticles = 5;
        ConferenceArticleListDriver::$word = "computer";
        ConferenceArticleListDriver::$conference = "2013 IEEE International Conference on Communications Workshops (ICC)";
        $json = ConferenceArticleListDriver::getConferenceArticleList();
        json_decode($json);

        $this->assertEquals(json_last_error(), JSON_ERROR_NONE);

    }


    public function testGetConferenceArticleListIEEE()
    {
        ConferenceArticleListDriver::$articleList =array();
        ConferenceArticleListDriver::$articles = array();
        ConferenceArticleListDriver::$numArticles = 5;
        ConferenceArticleListDriver::$word = "computer";
        ConferenceArticleListDriver::$conference = "2013 IEEE International Conference on Communications Workshops (ICC)";
        ConferenceArticleListDriver::getIEEE();

        $bool = strcmp(ConferenceArticleListDriver::$articles[0]->conferences, "2013 IEEE International Conference on Communications Workshops (ICC)") == 0;

        $this->assertEquals($bool && sizeof(ConferenceArticleListDriver::$articles)==5, true);

    }

    public function testGetConferenceArticleListACM()
    {
        ConferenceArticleListDriver::$articleList =array();
        ConferenceArticleListDriver::$articles = array();
        ConferenceArticleListDriver::$numArticles = 5;
        ConferenceArticleListDriver::$word = "computer";
        ConferenceArticleListDriver::$conference = "Proceedings of the 4th international conference on Knowledge capture  - K-CAP '07";
        ConferenceArticleListDriver::getACM();

        $bool = strcmp(ConferenceArticleListDriver::$articles[0]->conferences, "Proceedings of the 4th international conference on Knowledge capture  - K-CAP '07") == 0;

        $this->assertEquals($bool && sizeof(ConferenceArticleListDriver::$articles)==5, true);

    }

    public function testParseACM(){
        ConferenceArticleListDriver::$articleList =array();
        ConferenceArticleListDriver::$articles = array();
        ConferenceArticleListDriver::$numArticles = 5;
        ConferenceArticleListDriver::$word = "computer";
        ConferenceArticleListDriver::$conference = "2013 IEEE International Conference on Communications Workshops (ICC)";
        $article = new Article("http:\/\/dx.doi.org\/10.1145\/1275152.1275160", "", "", "", 0, 0);
        ConferenceArticleListDriver::parseACM($article);

        $this->assertEquals(empty(ConferenceArticleListDriver::$articleList), false);

    }

    public function testParseIEEE(){
        ConferenceArticleListDriver::$articleList =array();
        ConferenceArticleListDriver::$articles = array();
        ConferenceArticleListDriver::$numArticles = 5;
        ConferenceArticleListDriver::$word = "computer";
        ConferenceArticleListDriver::$conference = "2013 IEEE International Conference on Communications Workshops (ICC)";
        $article = new Article("", "", "", "", 1, 4107932);
        ConferenceArticleListDriver::parseIEEE($article);

        $this->assertEquals(empty(ConferenceArticleListDriver::$articleList), false);

    }

    public function testCountWordOccurences(){
        ConferenceArticleListDriver::$articleList =array();
        ConferenceArticleListDriver::$articles = array();
        ConferenceArticleListDriver::$numArticles = 5;
        ConferenceArticleListDriver::$word = "computer";
        ConferenceArticleListDriver::$conference = "2013 IEEE International Conference on Communications Workshops (ICC)";
        $string = "When the count Occurence runs on the computer it should count the word computer about computer amount of times";
        $count = ConferenceArticleListDriver::countWordOccurence($string);

        $this->assertEquals($count, 3);
    }

}