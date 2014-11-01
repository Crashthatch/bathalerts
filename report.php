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

include('header.php');

?>

    <body>
        <?php if ($pc) { ?>
            <div id="map"></div>
            <script src="library/js/map.js"></script>
            
            <!-- If the post code is defined show data -->
        <?php } else { ?>
            <!-- Redirect to initial page -->
        <?php } ?>
    </body>
</html>