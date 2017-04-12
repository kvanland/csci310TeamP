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
$id = "1455209";
$database = "0";

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
            $url = "http://dl.acm.org.libproxy1.usc.edu/tab_abstract.cfm?id=$id&type=Article&usebody=tabbody&_cf_containerId=abstract&_cf_nodebug=true&_cf_nocache=true&_cf_clientid=36E85E46A5834633E63A802CD6C7FE27&_cf_rc=0";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7) AppleWebKit/534.48.3 (KHTML, like Gecko) Version/5.1 Safari/534.48.3');

            $content = curl_exec($ch);

            echo $content;

            curl_close($ch);
        }






    }
}