<?php
/**
 * Created by PhpStorm.
 * User: peterzolintakis
 * Date: 4/11/17
 * Time: 5:02 PM
 */

$conference = $_GET["conference"];


echo ConferenceArticleListDriver::getConferenceArticleList($conference);


class ConferenceArticleListDriver{

    public static function getConferenceArticleList($conference){
        $IEEETitles = ConferenceArticleListDriver::getIEEE($conference);
        $ACMTitles = ConferenceArticleListDriver::getACM($conference);

        $conferenceTitles = array_merge($IEEETitles, $ACMTitles);


        $sendObj = array();
        foreach ($conferenceTitles as $title) {
            array_push($sendObj, array("title"=>$title));
        }

        return json_encode($sendObj);
    }

    public static function getIEEE($conference)
    {
        $titles = array();
        $url = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?jn=$conference&hc=1000";

        $xml = simplexml_load_file($url);
        if (empty($xml))
            return $titles;

        for ($i = 0; $i < sizeof($xml->document); $i++) {
            $xmlConference = $xml->document[$i]->pubtitle;
            if ($xmlConference == $conference) {
                $title = (string) $xml->document[$i]->title;
                array_push($titles, $title);
            }
        }

        return $titles;
    }

    public static function getACM($conference){
        $titles = array();
        $urlConference = str_replace(" ", "+", $conference);
        $url = "http://api.crossref.org/works?filter=member:320&query=$urlConference&sort=score&order=desc&rows=1000";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $json = curl_exec($ch);
        curl_close($ch);

        $acmArray = json_decode($json, true);

        foreach ($acmArray["message"]["items"] as $article){
            if(array_key_exists("event", $article))
                if(array_key_exists("name", $article["event"])) {
                    $acmConference = $article["event"]["name"];
                    if(strcmp($acmConference,$conference) == 0)
                        array_push($titles,$article["title"][0]);
                }
        }
        return $titles;


    }

}
