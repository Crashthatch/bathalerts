<?php

class PlanningApplication extends Module {

    const MAX_DISTANCE = 1;  // In Km
    public $url = "https://data.bathhacked.org/resource/uyh5-eygi.json";
    
    function getData() {
        $aps = json_decode($this->fetch());
        $relevant = array();
        foreach($aps as $ap) {
            if(!property_exists($ap, 'locationtext')) {
                continue;
            }
            preg_match(Module::POSTCODE_REGEX, $ap->locationtext, $matches);
            
            // If we find the planning permission
            if(isset($matches[0])) {
                $apl = $this->getPostCodeLocation($matches[0]);
                
                // If we can convert it to a location
                if($apl) {
                    $distance = $this->distance($apl[1], $apl[0], 
                        self::$postCodeLoc[1], self::$postCodeLoc[0], "K");
                        
                    // Add only planning applications in our area
                    if($distance < self::MAX_DISTANCE) {
                        $relevant[] = array(
                            'casedate'     => $ap->casedate,
                            'casetext'     => $ap->casetext,
                            'locationtext' => $ap->locationtext,
                            'banesstatus'  => $ap->banesstatus
                        );
                    }
                }
                
            }
        }
        return $relevant;
    }  
}

?>