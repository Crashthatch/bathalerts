<?php

include "Database.php";
include "Modules/Point.php";
include "Modules/Module.php";
include "Modules/Crime.php";
include "Modules/PlanningApplication.php";
include "Modules/HousePrice.php";
include "Modules/Floods.php";

// Checking email form
$emailAdded = false;
if(isset($_POST['email']) && isset($_POST['user-lat']) && 
        isset($_POST['user-long']) && isset($_POST['crime']) && 
        isset($_POST['planning']) && isset($_POST['houses']) &&
        isset($_POST['flooding']) && isset($_POST['radius'])) {
        
    $email    = $conn->real_escape_string($_POST['email']);
    $userLat  = $conn->real_escape_string($_POST['user-lat']);
    $userLong = $conn->real_escape_string($_POST['user-long']);
    $radius   = $conn->real_escape_string($_POST['radius']);
    
    $houses   = ($_POST['houses'] == 'Yes' ? "TRUE" : "FALSE");
    $crime    = ($_POST['crime'] == 'Yes' ? "TRUE" : "FALSE");
    $planning = ($_POST['planning'] == 'Yes' ? "TRUE" : "FALSE");
    $flooding = ($_POST['flooding'] == 'Yes' ? "TRUE" : "FALSE");
    
    $conn->query(
        "INSERT IGNORE INTO Users " . 
            "(`Email`, `UserLat`, `UserLong`, `Crime`, `Planning`, `Houses`, `Flooding`, `Radius`) " .
        "VALUES " . 
            "('$email', '$userLat', '$userLong', $crime, $planning, $houses, $flooding, $radius)");
            
    $emailAdded = true;
}

$pc = "";
if (isset($_GET['pc'])) {
    $pc = new Point($_GET['pc']);

    if(!$pc->exists()) {
        header('Location: /index.php?unknownPostcode=1');
    }
} 

else {
    header('Location: /index.php?noPostcode=1') ;
}

$pa = new PlanningApplication($pc);
$planningData = $pa->getData();
$crimeGetter = new Crime($pc);
$crimeData = $crimeGetter->getData();
$hd = new HousePrice($pc);
$houseData = $hd->getData();
$floodGetter = new Floods($pc);
$floodData = $floodGetter->getData();

include_once('header.php');

?>
    <body class="report">
        <header>
            <div class="wrap">
                <div class="first">
                    <h1>BathAlerts</h1>
                    <h2>Get monthly email alerts about things near you</h2>
                </div>
            </div>
        </header>

        <section id="map-section">
            <div class="wrap">
                <div class="first">
                    <h3>Showing information local to <span><?php echo $pc->toString(); ?></span><a href="/">Change</a></h3>
                </div>

                <div class="last">
                    <h3>Custom search distance <a id="custom-search-button">Change</a></h3>
                </div>
            </div>

            <?php if ($pc) { ?>
                <script type="application/javascript">
                    var emailAdded = <?php echo ($emailAdded ? "true" : "false") ?>;
                    var floodData = <?php echo json_encode($floodData); ?>;
                    var crimeData = <?php echo json_encode($crimeData); ?>;
                    var planningData = <?php echo json_encode($planningData); ?>;
                    var houseData = <?php echo json_encode($houseData); ?>;
                    var searchedForPostcode = <?php echo json_encode(array($pc->lat, $pc->long)); ?>;
                </script>

                <div id="map"></div>
            <?php } ?>
        </section>

        <section id="list-section">
            <div class="wrap">
                <div class="subscribe-info">
                    <h3 class="first">Customise your monthly email alerts by ticking the sections on or off below.</h3>
                    <button class="last">Create Alert</button>
                </div>

                <form method="post">
                    <input type="hidden" name="radius" id="user-radius" value="500" checked>
                    <div class="inner-form">
                        <div id="flood-risk" class="clearfix">
                            <input type="checkbox" name="flooding" id="flood-risk_check" value="Yes" checked>
                            <label for="flood-risk_check">Flood Risk</label>

                            <ul>
                                <?php foreach($floodData as $flood) {
                                    echo '<li></li>';
                                } ?>
                            </ul>
                        </div>

                        <div id="planning-applications" class="fourcol first">
                            <input type="checkbox" name="planning" id="planning-applications_check" value="Yes" checked>
                            <label for="planning-applications_check">Planning Applications</label>

                            <ul>
                                <?php foreach($planningData as $plan) {
                                    echo '<li><strong>' . 
                                    date("jS F, Y", strtotime(str_replace("T", " ", $plan['casedate']))) . " - " .
                                    $plan['banesstatus'] . '</strong><br /><span>' . 
                                    $plan['locationtext'] . '</span><br /><span><em>' . 
                                    $plan['casetext'] . '</span></em></li>';
                                } ?>
                            </ul>
                        </div>

                        <div id="crimes" class="fourcol">
                            <input type="checkbox" name="crime" id="crimes_check" value="Yes" checked>
                            <label for="crimes_check">Crimes</label>

                            <ul>
                                <?php foreach ($crimeData as $crime) {
                                    $crime_nice_name = str_replace("-", " ", $crime['crime_category']);
                                    echo '<li><strong>' . date("F, Y", strtotime(str_replace("T", " ", $crime['month']))) . " " . ' - ' . $crime_nice_name . '</strong><br />' . $crime['street_name'] . '</li>';
                                } ?>
                            </ul>
                        </div>

                        <div id="house-sales" class="fourcol last">
                            <input type="checkbox" name="houses" id="house-sales_check" value="Yes" checked>
                            <label for="house-sales_check">House Sales</label>

                            <ul>
                                <?php foreach($houseData as $houses) {
                                    $addr = (isset($houses['secondary_addressable_object_name']) ? 
                                        $houses['secondary_addressable_object_name'] : "");
                                    echo '<li><strong>' . 
                                    date("jS F, Y", strtotime(str_replace("T", " ", $houses['date_of_transfer']))) . ' - £' . 
                                    number_format($houses['price']) . '</strong><br />' . 
                                    ($addr ? strtolower($addr) . ', ' : "") . 
                                    strtolower($houses['locality']) . ', ' . 
                                    strtolower($houses['district']) . ', <span>' . 
                                    strtolower($houses['postcode']) . '</span></li>';
                                } ?>
                            </ul>
                        </div>
                    </div>

                    <div class="form-elements">
                        <div>
                            <h2>Create your Alert</h2>

                            <p>Type your email address and click the mail icon to start your email subscription.<br />
                            <em>You can unsubscribe at any time using the link at the bottom of your email.</em></p>
                        </div>

                        <div>
                            <input type="hidden" name="user-lat" id="user-lat-hidden" value="<?php echo $pc->lat; ?>" />
                            <input type="hidden" name="user-long" id="user-long-hidden" value="<?php echo $pc->long; ?>" />
                            <input type="email" name="email" placeholder="Sign-up for email alerts" />
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-envelope-o"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>        

        <?php 
        $current_page = basename(__FILE__, '.php');
        include_once('footer.php'); ?>