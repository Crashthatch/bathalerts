<?php

class HousePrice extends Module {

    public $url = "https://data.bathhacked.org/resource/ifh9-xtsp.json";
    
    function getData() {
        $postcodelat = self::$postCodeLoc[0];
        $postcodelong = self::$postCodeLoc[1];

        $startDate = date('Y-m-d', time()-6*30*24*60*60);
        $endDate = date('Y-m-d');
        $this->url .= '?$where=date_of_transfer>%27'.$startDate.'%27%20AND%20date_of_transfer<%27'.$endDate.'%27%20AND%20within_circle(location,'.$postcodelong.','.$postcodelat.',1000)';

        echo $this->url;


        return json_decode($this->fetch(), true);
    }
}

?>