<?php

include 'Database.php';
include "Modules/Point.php";
include "Modules/Module.php";
include "Modules/Floods.php";
require_once 'Mandrill.php';

try {
    $mandrill = new Mandrill('0OId28XlVG165u_hkAteMg');

    $res = $conn->query("SELECT * FROM Users WHERE Flooding='1'");
    echo mysqli_num_rows($res)." Users who want flooding alerts found!";
    while($row = $res->fetch_assoc()) {
        $lat = $row['UserLat'];
        $long = $row['UserLong'];
        $pc = new Point(Array($lat, $long));

        $radius = $row['Radius'];
        $to = $row['Email'];
        
        // Get data
        $html = file_get_contents( "flood_email_template.htm" );

        $floodHtml = "";
        $floodGetter = new Floods($pc, $radius);

        $floodData = $floodGetter->getData();
        if( count($floodData['ProximityFloodAlerts']) > 0 ) {
            foreach ($floodData['ProximityFloodAlerts'] as $flood) {
                $floodHtml .= '<li><strong>' . date("jS F, Y", strtotime(str_replace("T", " ", $flood['FloodAlert']['Raised']))) . ' - Severity: ' . $flood['FloodAlert']['Severity'] . '</strong><br />' . $flood['FloodAlert']['AreaDescription'] . '</li>';
            }

            $html = str_replace('{{Floods}}', $floodHtml, $html);

            $message = array(
                'html' => $html,
                'subject' => "BathAlert: Flood Warning",
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
        else{
            echo "No flood alerts for user at ".$pc->toString();
        }
    }
} catch(Mandrill_Error $e) {
    echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    throw $e;
}

?>