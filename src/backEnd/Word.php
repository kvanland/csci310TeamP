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

    public function wordSeen() {
    	$this->occurrences += 1;

    }

    public function addArticle($article) {
    	if(!in_array($article, $this->articleList)) {
    		array_push($this->articleList, $article);
    	}
    }



}