<?php

use PHPUnit\Framework\TestCase;

class GetStatusTest extends TestCase
{

    public function testGetStatus(){
        session_start();
        $wordCloud = new WordCloud();
        $wordCloud->initializeArticleList("Andrea Zanella","author",6);
        echo"dick";
        print_r($wordCloud);
        $_SESSION["wordCloud"] = $wordCloud;
        //json_decode(StatusDriver::getStatus());
        $this->assertEquals(json_last_error(), JSON_ERROR_NONE);
    }

}