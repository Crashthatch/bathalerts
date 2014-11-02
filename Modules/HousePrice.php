<?php

class HousePrice extends Module {

    public $url = "https://data.bathhacked.org/resource/ifh9-xtsp.json";
    public $radius = 500;
    
    function getData() {
        $lat = $this->point->lat;
        $long = $this->point->long;
        $startDate = date('Y-m-d', time()-6*30*24*60*60);
        $endDate = date('Y-m-d');
        $this->url .= '?$where=date_of_transfer>%27'.$startDate.'%27%20AND%20date_of_transfer<%27'.$endDate.'%27%20AND%20within_circle(location,'.$lat.','.$long.','.$this->radius.')';
        return json_decode($this->fetch(), true);
    }
}

?>