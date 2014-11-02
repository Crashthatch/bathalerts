<?php

class Point {

    const POSTCODE_REGEX = '/(GIR ?0AA|[A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]([0-9ABEHMNPRV-Y])?)|[0-9][A-HJKPS-UW]) ?[0-9][ABD-HJLNP-UW-Z]{2})/';
    const PCODEDB = "Data/Postcodes.csv";

    static private $pCodeDb;
    public $pCodeString = false;
    public $lat  = false;
    public $long = false;

    /**
     * Class constructor.
     *
     * Accepts either a post cose or an array with two coordinates, lat first
     * and then longitutde.
     *
     * @param   mixed $pc       Either an array or a post code string
     */
    function __construct($pc) {
        if(is_array($pc)) {
            $this->lat  = $pc[0];
            $this->long = $pc[1];
        } else {
            $this->pCodeString = $pc;
            $handle = fopen(self::PCODEDB, "r");
            if(!self::$pCodeDb && $handle) { 
                while(($line = fgets($handle)) !== false) {
                    $fields = explode(",", $line);
                    $pct = strtoupper(str_replace(" ", "", $fields[0]));
                    self::$pCodeDb[$pct] = array(
                        floatval(trim($fields[5])), 
                        floatval(trim($fields[6]))
                    );
                }
            } else {
                // Error
            }
            fclose($handle);
            $pc = strtoupper(str_replace(" ", "", $pc));
            if(isset(self::$pCodeDb[$pc])) {
                $this->long  = self::$pCodeDb[$pc][0];
                $this->lat   = self::$pCodeDb[$pc][1];
            }
        }
    }

    function exists() {
        return ($this->lat ? true : false);
    }
    
    function toString() {
        if($this->pCodeString) {
            return $this->pCodeString;
        } else {
            return "coordinates {$this->lat},{$this->long}";
        }
    }
}

?>