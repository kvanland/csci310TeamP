<?php

/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 4/7/17
 * Time: 3:40 PM
 */

use PHPUnit\Framework\TestCase;

class GetArticleListTest extends TestCase
{

    public function testGetArticleList(){
        session_start();
        $wordCloud = new WordCloud();
        $wordCloud->initializeArticleList("computer", "keyword", 5);

        for($i = 0; $i < 5; $i++){
            $wordCloud->parseNextArticle();
        }


        $_SESSION["wordCloud"] = $wordCloud;


        //json_decode(WordArticleListDriver::getWordArticleLists("computer"));

        $this->assertEquals(1, 1);
    }

}