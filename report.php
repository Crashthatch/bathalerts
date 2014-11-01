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
        <?php if($pc) { ?>
            <?php $pa->getData(); ?>
            <!-- If the post code is defined show data -->
        <?php } else { ?>
            <!-- Redirect to initial page -->
        <?php } ?>
    </body>
</html>