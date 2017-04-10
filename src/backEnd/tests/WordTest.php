<?php



use PHPUnit\Framework\TestCase;

class WordTest extends TestCase
{
    protected $word;

    protected function setUp(){
        $this->word = new Word("computer");
    }

    public function testWord(){
        $this->assert_equals($this->word->word, "computer");

    }

    public function testAddFirstArticle(){
        $this->word->wordSeen("article");

        $existsBool = array_key_exists("article", $this->word->articleList);
        $intBool = false;
        if($existsBool)
            $intBool = $this->word->articleList["article"] == 1;

        $this->assert_equals($existsBool && $intBool, true);
    }

    public function testAddNotFirstArticle(){
        $this->word->wordSeen("article");

        $existsBool = array_key_exists("article", $this->word->articleList);
        $intBool = false;
        if($existsBool)
            $intBool = $this->word->articleList["article"] == 2;

        $this->assert_equals($existsBool && $intBool, true);
    }

    public function testOccurrences(){
        $this->assert_equals($this->word->occurrences, 1);
    }

    public function testWordSeen(){
        $word = new WordCloud("word");

        $word->wordSeen("article");

        $this->assertEquals($word->occurrences, 1);
    }


}