<?php

class PlanningApplication extends Module {

    public $url = "https://data.bathhacked.org/resource/uyh5-eygi.json";
    
    //function __construct($postCode) {
    //    parent::__construct($postCode);
    //    $this->postCode = $postCode;
    //}
    
    function getData() {
        $aps = json_decode($this->fetch());
        foreach($aps as $ap) {
            if(property_exists($ap, 'locationtext')) {
                preg_match(Module::POSTCODE_REGEX, $ap->locationtext, $matches);
                if(isset($matches[0])) {
                    
                }
            }
        }
    }

    
    
}

?>