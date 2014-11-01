<?php

class Crime extends Module {

    public $url = "https://data.bathhacked.org/resource/e46f-mhfs.json";
    public $pc  = "";

    
    function __construct($postcode) {
        $this->postcode = $postcode;

        //TODO: Get lat / long for postcode.
        $postcodelat = $this->postCodeLoc[0];
        $postcodelat = $this->postCodeLoc[1];

        $this->url .= '?$where=within_circle(location,'.$postcodelat.','.$postcodelong.',100)';
    }
    
    function getData() {

        $crimes = json_decode($this->fetch(), true);
        foreach($crimes as $crime) {
            var_dump($crime);
        }
    }
    
}

?>