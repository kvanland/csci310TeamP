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
//        echo $json;
//        print_r($json);
        $array = json_decode($json, true);
        $titleBool = $array[0]["title"] == "Education in computer science and computer engineering starts with computer architecture";
        $authorsBool = $array[0]["authors"][0] == "Yale N. Patt";
        $frequencyBool = $array[0]["frequency"] == 3;
        $conferenceBool = $array[0]["conference"] == "Proceedings of the 1996 workshop on Computer architecture education - WCAE-2 '96" ;
        $downloadBool = $array[0]["download"] == "http://dx.doi.org/10.1145/1275152.1275160" ;
        $bibtexBool = $array[0]["bibtex"] == "http://dl.acm.org/exportformats.cfm?id=1275160&expformat=bibtex";


        $this->assertEquals($titleBool && $authorsBool && $frequencyBool && $conferenceBool && $downloadBool && $bibtexBool && sizeof($array) == 2, true);
    }

}