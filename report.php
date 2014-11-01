<?php

include "Database.php";
include "Modules/Module.php";
include "Modules/Crime.php";
include "Modules/PlanningApplication.php";
include "Modules/HousePrice.php";

// Checking email form
$emailAdded = false;
if(isset($_POST['email']) && isset($_POST['postcode']) && isset($_POST['crime']) && isset($_POST['planning']) && isset($_POST['houses'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $postcode = $conn->real_escape_string($_POST['postcode']);
    
    $houses = ($_POST['crime'] == 'Yes' ? "TRUE", "FALSE");
    $crime = ($_POST['crime'] == 'Yes' ? "TRUE", "FALSE");
    $planning = ($_POST['planning'] == 'Yes' ? "TRUE", "FALSE");
    
    $conn->query("INSERT IGNORE INTO Users (`Email`, `PostCode`, `Crime`, `Planning`, `Houses`) VALUES ('$email', '$postcode', $crime, $planning, $houses)");
    $emailAdded = true;
}

$pc = "";
if(isset($_GET['pc'])) {
    $pc = strtoupper($_GET['pc']);
}
else {
    header( 'Location: index.php?noPostcode=1' ) ;
}

// See if this is a postcode we know about and can geo code.
if ( !Module::postcodeExists($pc) ){
    header( 'Location: index.php?unknownPostcode=1' ) ;
}

$searchedForPostcodeLocation = Module::getPostCodeLocation($pc);

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
                    <input type="email" name="email" placeholder="Sign-up for email alerts" />
                    <input type="text" name="postcode" id="pc-hidden" value="<?php echo $pc; ?>" />
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

                    var searchedForPostcode = <?php echo json_encode($searchedForPostcodeLocation); ?>;
                </script>

                <div id="map"></div>
                <script src="library/js/map.js"></script>
            <?php } else {} ?>
        </section>

        <footer></footer>
    </body>
</html>