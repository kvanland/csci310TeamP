<?php

use PHPUnit\Framework\TestCase;

class Article
{
    public $url;
    public $authors;
    public $conferences;
    public $name;

    function __construct($url, $authors, $conferences, $name){
        $this->url = $url;
        $this->authors = $authors;
        $this->conferences = $conferences;
        $this->name = $name;
    }
}