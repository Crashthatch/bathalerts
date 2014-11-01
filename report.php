<?php

include "Modules/Module.php";
include "Modules/Crime.php";
include "Modules/PlanningApplication.php";

$pc = "";
if(isset($_GET['pc'])) {
    $pc = $_GET['pc'];
}
$pa = new PlanningApplication($pc);
$planningData = $pa->getData();
$crimeGetter = new Crime($pc);
$crimeData = $crimeGetter->getData();

?>
<!DOCTYPE html>
<html>
	<head>
        <title>Bath Alerts</title>
        <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
        <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <link rel="stylesheet" href="map/map.css" />
        <script type="application/javascript">
            var crimeData = <?php echo json_encode($crimeData); ?>;
            var planningData = <?php echo json_encode($planningData); ?>;
        </script>
    </head>
    <body>
        <?php if($pc) { ?>
            <div id="map"></div>
            <script src="map/map.js"></script>
            <!-- If the post code is defined show data -->
        <?php } else { ?>
            <!-- Redirect to initial page -->
        <?php } ?>
    </body>
</html>