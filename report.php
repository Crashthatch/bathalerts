<?php

include "Modules/Module.php";
include "Modules/PlanningApplication.php";

$pc = "";
if(isset($_GET['pc'])) {
    $pc = $_GET['pc'];
}
$pa = new PlanningApplication($pc);
?>
<!DOCTYPE html>
<html>

	<head>
        <title>Bath Alerts</title>
    </head>
    <body>
        <?php $pa->getData(); ?>
    </body>
</html>