<?php
/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 4/9/17
 * Time: 6:57 PM
 */

include "Constants.php";

//$id = $_GET["title"];
//$database = $_GET["author"];
$id = "4568001";
$database = "1";

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
//            file_get_contents()
        }





    }
}