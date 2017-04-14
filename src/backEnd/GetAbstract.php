<?php
/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 4/9/17
 * Time: 6:57 PM
 */

include "Constants.php";
include "simple_html_dom.php";

$id = $_GET["title"];
$database = $_GET["author"];


echo GetAbstractDriver::getAbstract($id, $database);

class GetAbstractDriver{

    public static function getAbstract($id, $database){

        if($database == Constants::IEEE) {
            $url = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?an=$id";
            $xml = simplexml_load_file($url);

            $abstract = (string)$xml->document[0]->abstract;

            if(substr($abstract, 0,1) == "<")
                return "An abstract is not available";

            return $abstract;
        }
        else{
            $url = str_replace("\\", "", $id);
            $cr = curl_init($url);
            curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cr, CURLOPT_FOLLOWLOCATION, 1);
            curl_exec($cr);
            $info = curl_getinfo($cr);
            $url = $info["url"];
            $url = $url."&preflayout=flat";
            $html = file_get_html($url);

            if(empty($html))
                return "An abstract is not available";

            $abstractNotAvailable = empty($html->find("A[NAME=abstract]", 0)->parent()->next_sibling()->find("p", 0));
            if (!$abstractNotAvailable){
                $abstract = $html->find("A[NAME=abstract]", 0)->parent()->next_sibling()->find("p", 0)->innertext();
                return $abstract;
            }
            return "An abstract is not available";
        }






    }
}