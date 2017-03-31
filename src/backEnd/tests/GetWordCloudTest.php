<?php


use PHPUnit\Framework\TestCase;

class GetWordCloudTest extends TestCase
{

    public function testGetWordCloud(){
        json_decode(WordCloudDriver::getWordCloud("Andrea Zanella", "author", 20), true);
        $this->assertEquals(json_last_error(), JSON_ERROR_NONE);

    }


}