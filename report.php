<?php 

include "Modules/Module.php";
include "Modules/Crime.php";
include "Modules/PlanningApplication.php";
include "Modules/HousePrice.php";

$pc = "";

if(isset($_GET['pc'])) {
    $pc = $_GET['pc'];
}

$pa = new PlanningApplication($pc);
$planningData = $pa->getData();
$crimeGetter = new Crime($pc);
$crimeData = $crimeGetter->getData();
$hd = new HousePrice($pc);
$houseData = $hd->getData();

include('header.php');

?>

    <body class="report">

        <header>
            <div class="wrap">
                <h1>BathAlerts</h1>
                <h2>Get monthly email alerts about things near you</h2>
            </div>
        </header>

        <section>
            <div class="wrap">
                <h3>Showing information local to BA1 5EB<a href="/">Change</a></h3>
            </div>

            <?php if ($pc) { ?>
                <script type="application/javascript">
                    var crimeData = <?php echo json_encode($crimeData); ?>;
                    var planningData = <?php echo json_encode($planningData); ?>;
                    var houseData = <?php echo json_encode($houseData); ?>;
                </script>

                <script src="library/js/map.js"></script>
                <div id="map"></div>
            <?php } else {} ?>
        </section>

        <footer></footer>
    </body>
</html>