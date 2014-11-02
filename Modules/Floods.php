<?php

class Floods extends Module {
    
    function getData() {
        $lat = $this->point->lat;
        $long = $this->point->long;

        $apiKey = '127c9ae4-cc91-457c-bf5a-4b4d8fd38104';
        $url = 'https://apifa.shoothill.com/Account/APILogin/';

        $postinfo = "apikey=".$apiKey."&persist=false";

        $cookie_file_path = dirname(__FILE__) . "/tmp/cookie.txt";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('application/x-www-form-urlencoded'));

        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);

        curl_setopt($ch, CURLOPT_COOKIE, "cookiename=1");
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);

        $loginResult = curl_exec($ch);

        $requestUrl = "https://apifa.shoothill.com/api/proximityfloodalerts/$lat/$long/100000";
        echo $requestUrl;
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_URL, $requestUrl );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
    
}

?>