<?php

class Crime extends Module {

    public $url = "https://data.bathhacked.org/resource/e46f-mhfs.json";
    
    function getData() {
        $lat = $this->point->lat;
        $long = $this->point->long;
        $startDate = date('Y-m-d', time()-4*30*24*60*60);
        $endDate = date('Y-m-d');

        $rad = $this->radius;
        $rad = ($rad == 500 ? $rad / 2 : $rad);
        $this->url .= '?$where=month>%27'.$startDate.'%27%20AND%20month<%27'.$endDate.'%27%20AND%20within_circle(location,'.$lat.','.$long.','.$rad.')&$order=month%20DESC';
        return json_decode($this->fetch(), true);
    }
}

?>