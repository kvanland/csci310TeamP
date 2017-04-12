<?php



use PHPUnit\Framework\TestCase;

class GetConferenceArticleListTest extends TestCase
{

    public function testGetConferenceArticleListValidJson()
    {
        $json = ConferenceArticleListDriver::getConferenceArticleList("2013 IEEE International Conference on Communications Workshops (ICC)");
        $array = json_decode($json);

        $this->assertEquals(json_last_error(), JSON_ERROR_NONE);

    }


    public function testGetConferenceArticleListIEEE()
    {
        $array = ConferenceArticleListDriver::getIEEE("2013 IEEE International Conference on Communications Workshops (ICC)");

        $this->assertEquals(($array[0] == "Cooperative drug delivery through molecular communication among biological nanomachines" &&
        sizeof($array)==296), true);

    }

    public function testGetConferenceArticleListOther()
    {
        $array = ConferenceArticleListDriver::getACM("the 4th international conference");

        $this->assertEquals(($array[0] == "Proceedings of the 4th international conference on Knowledge capture  - K-CAP '07" &&
            sizeof($array)==328), true);

    }
}