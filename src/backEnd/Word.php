<?php



class Word
{

    public $word;
    public $articles;
    public $occurrences;

    public function word($word, $article){
        $this->word = $word;
        $this->articles = array($article);
        $this->occurrences = 1;
    }


}