<?php



use PHPUnit\Framework\TestCase;

class WordTest extends TestCase
{
    protected $word;

    protected function setUp(){
        $article = new Article("url1", array("author", "author1"), array("conference", "conference1"), "title1");
        $this->word = new Word("computer", $article);
    }

    public function testWord(){
        $this->assert_equals($this->word->word, "computer");

    }

    public function testArticles(){
        $article = new Article("url1", array("author", "author1"), array("conference", "conference1"), "title1");
        $array = array($article);
        $this->assert_equals($this->word->articles, $array);
    }

    public function testOccurrences(){
        $this->assert_equals($this->word->occurrences, 1);
    }


}