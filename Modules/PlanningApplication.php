<?php

class PlanningApplication extends Module {

    const MAX_DISTANCE = 1;  // In Km
    public $url = "https://data.bathhacked.org/resource/uyh5-eygi.json";
    
    function getData() {
        $aps = json_decode($this->fetch());
        foreach($aps as $ap) {
            if(property_exists($ap, 'locationtext')) {
                preg_match(Module::POSTCODE_REGEX, $ap->locationtext, $matches);
                // If we find the planning permission
                if(isset($matches[0])) {
                    $apl = $this->getPostCodeLocation($matches[0]);                 
                    // If we can convert it to a location
                    if($apl) {
                        $distance = $this->distance($apl[1], $apl[0], self::$postCodeLoc[1], self::$postCodeLoc[0], "K");
                        if($distance < self::MAX_DISTANCE) {
                            echo "<p>" . $distance . "kilometers</p>";
                        }
                    }
                    
                }
            }
        }   
    }  
}

?>