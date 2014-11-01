<?php

include "Modules/Module.php";
include "Modules/Crime.php";
include "Modules/PlanningApplication.php";
include "Modules/HousePrice.php";

// Checking email form
$emailAdded = false;
if(isset($_POST['email']) && isset($_POST['postcode'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $postcode = $conn->real_escape_string($_POST['postcode']);
    $conn->query("INSERT IGNORE INTO Users (`Email`, `PostCode`) VALUES ('$email', '$postcode')");
    $emailAdded = true;
}

$pc = "";
if(isset($_GET['pc'])) {
    $pc = strtoupper($_GET['pc']);
}
else{
    header( 'Location: index.php?noPostcode=1' ) ;
}
//See if this is a postcode we know about and can geo code.
if( !Module::postcodeExists($pc) ){
    header( 'Location: index.php?unknownPostcode=1' ) ;
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
                <div class="first">
                    <h1>BathAlerts</h1>
                    <h2>Get monthly email alerts about things near you</h2>
                </div>

                <form method="post" class="last">
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
                    var emailAdded = <?php echo ($emailAdded ? "true" : "false") ?>;
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