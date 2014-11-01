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

                <form method="post">
                    <input type="email" name="email">
                    <input type="text" name="postcode" placeholder="<?php echo $pc; ?>" id="pc-hidden">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-envelope-o"></i>
                    </button>
                </form>
            </div>
        </header>

        <section>
            <div class="wrap">
                <h3>Showing information local to <span><?php echo $pc; ?></span><a href="/">Change</a></h3>
            </div>

            <?php if ($pc) { ?>
                <script type="application/javascript">
                    var crimeData = <?php echo json_encode($crimeData); ?>;
                    var planningData = <?php echo json_encode($planningData); ?>;
                    var houseData = <?php echo json_encode($houseData); ?>;
                </script>

                <div id="map"></div>
                <script src="library/js/map.js"></script>
            <?php } else {} ?>
        </section>

        <footer></footer>
    </body>
</html>