<?php

use PHPUnit\Framework\TestCase;

class GetStatusTest extends TestCase{


    public function testGetStatusJSON(){
        $wc = new WordCloud();
        $wc->initializeArticleList("Andrea Zanella", "author", 5);
        $json = StatusDriver::getStatus($wc);
        json_decode($json);
        $this->assertEquals(json_last_error(), JSON_ERROR_NONE);
    }

    public function testGetStatusPercent(){
        $wc = new WordCloud();
        $wc->initializeArticleList("Andrea Zanella", "author", 5);
        $json = StatusDriver::getStatus($wc);
        $array = json_decode($json, true);

        $this->assertEquals($array["status"], 20);
    }

}