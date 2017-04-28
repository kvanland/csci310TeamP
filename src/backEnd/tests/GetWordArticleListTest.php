<?php

/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 4/7/17
 * Time: 3:40 PM
 */

use PHPUnit\Framework\TestCase;

class GetWordsArticleListTest extends TestCase
{

    public function testGetArticleList(){
        $wordCloud = new WordCloud();
        $wordCloud->initializeArticleList("computer", "keyword", 5);

        for($i = 0; $i < 5; $i++){
            $wordCloud->parseNextArticle();
//            echo "wcdata$i";
//            print_r($wordCloud->wcData);
        }

        $json = WordArticleListDriver::getWordArticleList("computer", $wordCloud);
        $array = json_decode($json, true);
        $titleBool = $array["articles"][0]["title"] == "Invariant Pattern Recognition Using Radial Tchebichef Moments";
        $authorsBool = $array["articles"][0]["authors"][0] == "Bin Xiao";
        $frequencyBool = $array["articles"][0]["frequency"] == 2;
        $conferenceBool = $array["articles"][0]["conference"] == "2010 Chinese Conference on Pattern Recognition (CCPR)" ;
        $downloadBool = $array["articles"][0]["download"] == "http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=5659179" ;
        $bibtexBool = $array["articles"][0]["bibtex"] == "http://ieeexplore.ieee.org/xpl/downloadCitations?recordIds=5659179&citations-format=citation-only&download-format=download-bibtex";
        // $boolArray = array($titleBool,$authorsBool,$frequencyBool,$conferenceBool,$downloadBool,$bibtexBool,sizeof($array) == 1);
        // print_r($boolArray);
        $this->assertEquals($titleBool && $authorsBool && $frequencyBool && $conferenceBool && $downloadBool && $bibtexBool && sizeof($array) == 1, true);
    }

}