<?php

include 'Database.php';
include "Modules/Module.php";
include "Modules/Crime.php";
include "Modules/PlanningApplication.php";
include "Modules/HousePrice.php";
require_once 'Mandrill.php';

try {
    $mandrill = new Mandrill('0OId28XlVG165u_hkAteMg');

    $res = $conn->query("SELECT * FROM Users");
    while($row = $res->fetch_assoc()) {
        $pc = $row['PostCode'];
        $to = $row['Email'];
        
        // Get data
        $html = "HTML GOES HERE!";
        
        if($row['Planning']) {
            $pa = new PlanningApplication($pc);
            $planningData = $pa->getData();
            foreach($planningData as $pa) {
                // Build pa html rows
                $html .= "";
            }
        }
        
        if($row['Crime']) {
            $crimeGetter = new Crime($pc);
            $crimeData = $crimeGetter->getData();
            foreach($crimeData as $cd) {
                // Build cd html rows
                $html .= "";
            }
        }
        
        if($row['Houses']) {
            $hd = new HousePrice($pc);
            $houseData = $hd->getData();        
            foreach($houseData as $hd) {
                // Build hd html rows
                $html .= "";
            }
        }
        
        $message = array(
            'html' => $html,
            'subject' => "Your $pc Alert",
            'from_email' => 'bathalerts@bathhacked.org',
            'from_name' => 'BathAlerts',
            'to' => array(
                array(
                    'email' => $to,
                    'name' => 'Bath Habitant',
                    'type' => 'to'
                )
            ),
            'headers' => array('Reply-To' => 'bathalerts@bathhacked.org'),
        );

        $async = false;
        $ip_pool = 'Main Pool';
        $result = $mandrill->messages->send($message, $async, $ip_pool);
        print_r($result);
    }
} catch(Mandrill_Error $e) {
    echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    throw $e;
}

?>