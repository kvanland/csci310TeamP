<?php



class Word
{

    public $word;
    public $articleList;
    public $occurrences;

    function __construct($word){
        $this->word = $word;
        $this->articleList = array();
        $this->occurrences = 1;
    }

    public function wordSeen($articleName) {
    	$this->occurrences += 1;
        $this->addArticle($articleName);

    }

    public function addArticle($article) {
    	if(!in_array($article, $this->articleList)) {
    		$this->articleList[$article] = 1;
    	}
    	else{
    	    $this->articleList[$article]++;
        }
    }



}