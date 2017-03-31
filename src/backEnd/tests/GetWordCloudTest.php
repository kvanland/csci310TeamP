<?php

/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 3/30/17
 * Time: 6:09 PM
 */

use PHPUnit\Framework\TestCase;

class GetWordCloudTest extends TestCase
{

    public function testGetWordCloudWithArticleResults(){
        $array = json_decode(WordCloudDriver::getWordCloud("Andrea Zanella", "author", 20));
        $this->assertEquals($array["result"], "success");

    }

    public function testGetWordCloudWithNoResults(){
        $array = json_decode(WordCloudDriver::getWordCloud("asdfhkladsf", "author", 20));
        $this->assertEquals($array["result"], "fail");
    }


}