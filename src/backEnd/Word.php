<?php



class Word
{

    public $word;
    public $articleList;
    public $occurrences;

    function __construct($word){
        $this->word = $word;
        $this->articleList = array();
        $this->occurrences = 0;
    }

    public function wordSeen($articleName) {
    	$this->occurrences += 1;
        $this->addArticle($articleName);

    }

    public function addArticle($article) {
        if(array_key_exists($article, $this->articleList)) {
            $this->articleList[$article]++;
    	}
    	else{
            $this->articleList[$article] = 1;
        }
    }



}