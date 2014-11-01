<?php

include "Modules/Module.php";
include "Modules/Crime.php";

$pc = "";
if(isset($_GET['pc'])) {
    $pc = $_GET['pc'];
}
$pa = new Crime($pc);
$crimeData = $pa->getData();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Bath Alerts</title>
    <script type="application/javascript">
        var crimeData = <?php echo json_encode($crimeData); ?>;
    </script>
</head>
<body>

</body>
</html>