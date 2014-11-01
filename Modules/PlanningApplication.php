<?php

class PlanningApplication extends Module {

    public $url = "https://data.bathhacked.org/resource/uyh5-eygi.json";
    public $postCode  = "";
    
    
    function __construct($postCode) {
        parent::__construct();
        $this->postCode = $postCode;
    }
    
    function getData() {
        $aps = json_decode($this->fetch());
        foreach($aps as $ap) {
            preg_match(Module::POSTCODE_REGEX, $ap->locationtext, $matches);
            if(isset($matches[0])) {
                echo print_r($this->getPostCodeLocation($matches[0]));
            }
        }
    }

    
    
}

?>