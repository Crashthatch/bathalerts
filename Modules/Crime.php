<?php

class Crime extends Module {

    public $url = "https://data.bathhacked.org/resource/e46f-mhfs.json";

    
    function __construct($postcode) {
        parent::__construct($postcode);

        $postcodelat = self::$postCodeLoc[0];
        $postcodelong = self::$postCodeLoc[1];

        $this->url .= '?$where=within_circle(location,'.$postcodelong.','.$postcodelat.',1000)';
    }
    
    function getData() {

        $crimes = json_decode($this->fetch(), true);
        /*foreach($crimes as $crime) {
            var_dump($crime);
        }*/
        return $crimes;
    }
    
}

?>