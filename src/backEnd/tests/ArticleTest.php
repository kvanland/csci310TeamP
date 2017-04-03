<?php

use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    protected $article;

    protected function setUp()
    {
        $this->article = new Article("url",array("author1","author2"), array("conference1","conference2"),"name", 1, 0);
    }

    public function testURL(){
        $this->assertEquals($this->article->url, "url");
    }

    public function testAuthors(){
    	$this->assertEquals($this->article->authors[0], "author1");
    }

    public function testConferences(){
    	$this->assertEquals($this->article->conferences[0], "conference1");
    }

    public function testName(){
        $this->assertEquals($this->article->name, "name");
    }

    public function testDatabase(){
        $this->assertEquals($this->article->database, 1);
    }
}