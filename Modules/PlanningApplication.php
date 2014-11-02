<?php

class PlanningApplication extends Module {

    const MAX_DISTANCE = 1;  // In Km
    public $url = "https://data.bathhacked.org/resource/uyh5-eygi.json";
    
    function getData() {
        
        echo date("d/m/Y");
        
        $this->url .= '?$where=casedate>%2709/01/2014%2012:00:00%20AM%27';
        $aps = json_decode($this->fetch());
        $relevant = array();
        foreach($aps as $ap) {
            if(!property_exists($ap, 'locationtext')) {
                continue;
            }
            preg_match(Point::POSTCODE_REGEX, $ap->locationtext, $matches);
            
            // If we find the planning permission
            if(isset($matches[0])) {
                $apl = new Point($matches[0]);
                //$apl = $this->getPostCodeLocation($matches[0]);
                
                // If we can convert it to a location
                if($apl->exists()) {
                    $distance = $this->distance(
                        $apl->lat, $apl->long, 
                        $this->point->lat, $this->point->long, "K");
                        
                    // Add only planning applications in our area
                    if($distance < self::MAX_DISTANCE) {
                        $relevant[] = array(
                            'casedate'      => $ap->casedate,
                            'casetext'      => $ap->casetext,
                            'locationtext'  => $ap->locationtext,
                            'banesstatus'   => $ap->banesstatus,
                            'casereference' => $ap->casereference,
                            'location'      => array(
                                "longitude" => $apl->lat, 
                                "latitude"  => $apl->long)
                        );
                    }
                }
                
            }
        }
        return $relevant;
    }  
}

?>