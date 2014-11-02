<?php

class PlanningApplication extends Module {

    const MAX_DISTANCE = 0.5;  // In Km
    public $url = "https://data.bathhacked.org/resource/uyh5-eygi.json";
    
    function getData() {
        $date = new DateTime(date("Y-m-d H:i:s"));
        $date->modify("last day last month");
        $date = $date->format("Y-m-d") . "T00:00:00";
        $this->url .= '?$where=casedate>%27' . $date . '%27';
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