<?php

/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 4/20/17
 * Time: 4:30 PM
 */

use PHPUnit\Framework\TestCase;

include_once "WordCloud.php";

class GetSubsetWordCloudTest extends TestCase
{

    public function testGetSubsetWordCloud(){
        $a1 = new Article("url", array("seemore butts", "Mike hawk"), "The best conference", "Mike hunt", 1, "33");
        $a2 = new Article("tilts", array("larry", "mike", "porky"), "Coaaaaaa", "hunt", 0, "9");
        $a3 = new Article("feathery.com", array("lay"), "lokak", "jerry", 1, "9");
        $articleArray = array($a1, $a2, $a3);

        $wc = new WordCloud();
        SubsetDriver::setArticleList($wc, $articleArray);
        $this->assertEquals($wc->articleList,$articleArray);
    }

    public function testJson(){
        $a1 = new Article("url", array("seemore butts", "Mike hawk"), "The best conference", "Mike hunt", 1, "33");
        $a2 = new Article("tilts", array("larry", "mike", "porky"), "Coaaaaaa", "hunt", 0, "9");
        $a3 = new Article("feathery.com", array("lay"), "lokak", "jerry", 1, "9");
        $articleArray = array($a1, $a2, $a3);

        $wc = new WordCloud();
        $json = json_decode(SubsetDriver::setArticleList($wc, $articleArray));
        $this->assertEquals(json_last_error(),JSON_ERROR_NONE);
    }

}