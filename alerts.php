<?php

include 'Database.php';
include "Modules/Point.php";
include "Modules/Module.php";
include "Modules/Crime.php";
include "Modules/PlanningApplication.php";
include "Modules/HousePrice.php";
require_once 'Mandrill.php';

try {
    $mandrill = new Mandrill('0OId28XlVG165u_hkAteMg');

    $res = $conn->query("SELECT * FROM Users");
    while($row = $res->fetch_assoc()) {
        $lat = $row['UserLat'];
        $long = $row['UserLong'];
        $pc = new Point(Array($lat, $long));

        $radius = $row['Radius'];
        $to = $row['Email'];
        
        // Get data
        $html = file_get_contents( "email_template.htm" );

        $planningHtml = "";
        if($row['Planning']) {
            $pa = new PlanningApplication($pc, $radius);
            $planningData = $pa->getData();
            foreach($planningData as $plan) {
                // Build pa html rows
                $planningHtml .=  '<li><strong>' .
                    date("jS F, Y", strtotime(str_replace("T", " ", $plan['casedate']))) . " - " .
                    $plan['banesstatus'] . '</strong><br /><span>' .
                    $plan['locationtext'] . '</span><br /><span><em>' .
                    $plan['casetext'] . '</span></em></li>';
            }
        }

        $html = str_replace( '{{PlanningApplications}}', $planningHtml, $html );

        $crimeHtml = "";
        if($row['Crime']) {
            $crimeGetter = new Crime($pc, $radius);
            $crimeData = $crimeGetter->getData();
            foreach($crimeData as $crime) {
                $crime_nice_name = str_replace("-", " ", $crime['crime_category']);
                $crimeHtml .= '<li><strong>' . date("F, Y", strtotime(str_replace("T", " ", $crime['month']))) . " " . ' - ' . $crime_nice_name . '</strong><br />' . $crime['street_name'] . '</li>';
            }
        }

        $html = str_replace( '{{Crimes}}', $crimeHtml, $html );

        $houseHtml = "";
        if($row['Houses']) {
            $hd = new HousePrice($pc, $radius);
            $houseData = $hd->getData();        
            foreach($houseData as $houses) {
                $addr = (isset($houses['secondary_addressable_object_name']) ?
                    $houses['secondary_addressable_object_name'] : "");
                $houseHtml .= '<li><strong>' .
                    date("jS F, Y", strtotime(str_replace("T", " ", $houses['date_of_transfer']))) . ' - £' .
                    number_format($houses['price']) . '</strong><br />' .
                    ($addr ? ucwords(strtolower($addr)) . ', ' : "") .
                    ucwords(strtolower($houses['locality'])) . ', ' .
                    ucwords(strtolower($houses['district'])) . ', <span>' .
                    strtoupper($houses['postcode']) . '</span></li>';
            }
        }

        $html = str_replace( '{{HouseSales}}', $houseHtml, $html );

        $html = str_replace( '{{lat}}', $lat, $html );
        $html = str_replace( '{{long}}', $long, $html );

        $message = array(
            'html' => $html,
            'subject' => "Your Bath Alert",
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

        echo $html;

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