<?php


use PHPUnit\Framework\TestCase;

class GetWordCloudTest extends TestCase
{

    public function testGetWordCloudWithArticleResults(){
        $json = json_decode(WordCloudDriver::getWordCloud("Andrea Zanella", "author", 20), true);
        $this->assertEquals($json["result"], "success");

    }

    public function testGetWordCloudWithNoResults(){
        $json =json_decode(WordCloudDriver::getWordCloud("asdfhkladsf", "author", 20), true);
        $this->assertEquals($json["result"], "fail");
    }


}