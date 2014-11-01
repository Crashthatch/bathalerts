<?php

class PlanningApplication extends Module {

    public $url = "https://data.bathhacked.org/resource/uyh5-eygi.json";
    public $pc  = "";
    
    
    function __construct($pc) {
        parent::__construct();
        $this->pc = $pc;
    }
    
    function getData() {
        $aps = json_decode($this->fetch());
        foreach($aps as $ap) {  
            preg_match('/(GIR ?0AA|[A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]([0-9ABEHMNPRV-Y])?)|[0-9][A-HJKPS-UW]) ?[0-9][ABD-HJLNP-UW-Z]{2})/', $ap->locationtext, $matches);
        }
    }
    
}

?>