<?php

class Crime extends Module {

    public $url = "https://data.bathhacked.org/resource/e46f-mhfs.json";
    
    function getData() {
        $lat = $this->point->lat;
        $long = $this->point->long;
        $this->url .= '?$where=month>%272014-08-01%27%20AND%20month<%272014-11-01%27%20AND%20within_circle(location,'.$long.','.$lat.',1000)';
        return json_decode($this->fetch(), true);
    }
    
}

?>