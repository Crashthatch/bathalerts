<?php

abstract class Module {

    const POSTCODE_REGEX = '/(GIR ?0AA|[A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]([0-9ABEHMNPRV-Y])?)|[0-9][A-HJKPS-UW]) ?[0-9][ABD-HJLNP-UW-Z]{2})/';
    protected static $postCodes = false;
    protected static $postCode;
    protected static $postCodeLoc;
    private $tkn = "";
    
    function __construct($postCode) {
        self::$postCode = $postCode;
        // Load post code locations for the BANES area
        if(!self::$postCodes) {
            $handle = fopen("Postcodes.csv", "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $fields = explode(",", $line);
                    $pc = str_replace(" ", "", $fields[0]);
                    self::$postCodes[strtoupper($pc)] = array($fields[5], $fields[6]);                 
                }
            } else {
                // Error
            } 
            fclose($handle);
            self::$postCodeLoc = $this->getPostCodeLocation($postCode);
        } 

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

    function getPostCodeLocation($pc) {
        $pc = strtoupper(str_replace(" ", "", $pc));
        return (isset(self::$postCodes[$pc]) ? self::$postCodes[$pc] : false);
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