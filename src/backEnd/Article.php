<?php

use PHPUnit\Framework\TestCase;

class Article
{
    public $url;
    public $authors;
    public $conferences;
    public $name;
    public $database;

    function __construct($url, $authors, $conferences, $name, $database){
        $this->url = $url;
        $this->authors = $authors;
        $this->conferences = $conferences;
        $this->name = $name;
        $this->database = $database;

    }
}