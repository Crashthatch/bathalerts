<?php

class HousePrice extends Module {

    public $url = "https://data.bathhacked.org/resource/ifh9-xtsp.json";
    
    function getData() {
        $lat = $this->point->lat;
        $long = $this->point->long;
        $this->url .= '?$where=date_of_transfer>%272014-01-01%27%20AND%20date_of_transfer<%272014-11-01%27%20AND%20within_circle(location,'.$long.','.$lat.',1000)';
        return json_decode($this->fetch(), true);
    }
}

?>