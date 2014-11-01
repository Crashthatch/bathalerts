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
        <script type="application/javascript">
            var crimeData = <?php echo json_encode($crimeData); ?>;
            var planningData = <?php echo json_encode($planningData); ?>;
        </script>
    </head>
    <body>
        <?php if($pc) { ?>
            DASHBOARD!
            <!-- If the post code is defined show data -->
        <?php } else { ?>
            <!-- Redirect to initial page -->
        <?php } ?>
    </body>
</html>