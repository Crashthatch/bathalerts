<?php

abstract class Module {

    protected $point;
    protected $radius;
    private $tkn = "";

    function __construct($point, $radius = 500) {
        $this->point  = $point;
        $this->radius = $radius;
    }

    function fetch() {
        $opts = array(
            'http' => array(
                'method'=> "GET",
                'header'=>  "Accept: application/json\r\n" .
                            "Content-type: application/json\r\n" .
                            "X-App-Token: " . $this->tkn
            )
        );
        $c = stream_context_create($opts);
        return file_get_contents($this->url, false, $c);
    }

    // Calculate distance between coordinates
    function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        $lat1 = floatval($lat1);
        $lon1 = floatval($lon1);
        $lat2 = floatval($lat2);
        $lon2 = floatval($lon2);
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}

?>