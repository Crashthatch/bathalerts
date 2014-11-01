<?php

abstract class Module {

    protected static $postCodes = false;
    private $tkn = "";
    
    function __construct() {
        // Load post code locations for the BANES area
        if(!self::$postCodes) {
            $handle = fopen("Postcodes.csv", "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $fields = explode(",", $line);
                    $postCode = str_replace(" ", "", $fields[0]);
                    self::$postCodes[$postCode] = array($fields[5], $fields[6]);                 
                }
            } else {
                // Error
            } 
            fclose($handle);
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
}

?>