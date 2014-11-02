<?php

include "Database.php";
include "Modules/Point.php";
include "Modules/Module.php";
include "Modules/Crime.php";
include "Modules/PlanningApplication.php";
include "Modules/HousePrice.php";
include "Modules/Floods.php";
include "Mandrill.php";

// Checking email form
$emailAdded = false;

if(isset($_POST['email']) && isset($_POST['user-lat']) && 
        isset($_POST['user-long']) && isset($_POST['radius']) && $_POST['email'] != "") {
        
    $email    = $conn->real_escape_string($_POST['email']);
    $userLat  = $conn->real_escape_string($_POST['user-lat']);
    $userLong = $conn->real_escape_string($_POST['user-long']);
    $radius   = $conn->real_escape_string($_POST['radius']);
    
    $houses   = (isset($_POST['houses']) ? "TRUE" : "FALSE");
    $crime    = (isset($_POST['crime']) ? "TRUE" : "FALSE");
    $planning = (isset($_POST['planning']) ? "TRUE" : "FALSE");
    $flooding = (isset($_POST['flooding']) ? "TRUE" : "FALSE");
    
    $conn->query(
        "INSERT IGNORE INTO Users " . 
            "(`Email`, `UserLat`, `UserLong`, `Crime`, `Planning`, `Houses`, `Flooding`, `Radius`) " .
        "VALUES " . 
            "('$email', '$userLat', '$userLong', $crime, $planning, $houses, $flooding, $radius)");
            
    $emailAdded = true;

    //Send the welcome email.
    $mandrill = new Mandrill('0OId28XlVG165u_hkAteMg');
    // Get data
    $html = file_get_contents( "welcome_email_template.htm" );

    $message = array(
        'html' => $html,
        'subject' => "Welcome to Bath Alerts",
        'from_email' => 'bathalerts@bathhacked.org',
        'from_name' => 'BathAlerts',
        'to' => array(
            array(
                'email' => $email,
                'name' => 'Bath Habitant',
                'type' => 'to'
            )
        ),
        'headers' => array('Reply-To' => 'bathalerts@bathhacked.org'),
    );
    $async = false;
    $ip_pool = 'Main Pool';
    $result = $mandrill->messages->send($message, $async, $ip_pool);

}

$pc = "";
// Check if we are looking up post code
if(isset($_GET['pc']) && !isset($_POST['user-long'])) {
    $pc = new Point($_GET['pc']);
    if(!$pc->exists()) {
        header('Location: /index.php?unknownPostcode=1');
    }
// Check if user has customized point location
} elseif(isset($_POST['user-long']) && isset($_POST['user-lat'])) {
    $pc = new Point(array($_POST['user-lat'], $_POST['user-long']));
} else {
    header('Location: /index.php?noPostcode=1') ;
}

$rad = 500;
// Check for custom radius
if(isset($_POST['radius']) && is_numeric($_POST['radius'])) {
    $rad = $_POST['radius'];
}


$pa = new PlanningApplication($pc, $rad);
$planningData = $pa->getData();
$crimeGetter = new Crime($pc, $rad);
$crimeData = $crimeGetter->getData();
$hd = new HousePrice($pc, $rad);
$houseData = $hd->getData();
$floodGetter = new Floods($pc, $rad);
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
                    var searchedForPostcode = <?php echo json_encode(array($pc->long, $pc->lat)); ?>;
                </script>

                <div id="map"></div>
            <?php } ?>
        </section>

        <section id="list-section">
            <div class="wrap">
                <div class="subscribe-info">
                    <h3 class="first">Customise your monthly email alerts by ticking the sections on or off below.</h3>
                    <a href="#alert-section" class="button scroll last"><i class="fa fa-envelope-o"></i> Create Alert</a>
                </div>

                <form method="post">
                    <input type="hidden" name="radius" id="user-radius" value="<?= $rad ?>" checked>
                    <div class="inner-form">
                        <div id="flood-risk" class="clearfix">
                            <input type="checkbox" name="flooding" id="flood-risk_check" value="Yes" checked>
                            <label for="flood-risk_check">Flood Risks</label>

                            <ul>
                                <?php 
                                    foreach($floodData['ProximityFloodAlerts'] as $flood) {
                                        echo '<li><strong>' . date("jS F, Y", strtotime(str_replace("T", " ", $flood['FloodAlert']['Raised']))) . ' - Severity: ' . $flood['FloodAlert']['Severity'] . '</strong><br />' . $flood['FloodAlert']['AreaDescription'] . '</li>';
                                    }
                                ?>
                            </ul>

                            <?php 
                                $no_floods = array_filter($floodData['ProximityFloodAlerts']);
                                    
                                if (empty($no_floods)) {
                                    echo '<p>Hurrah! There are no floods in your area at the moment.</p>';
                                }
                            ?>
                        </div>

                        <div id="planning-applications" class="fourcol first">
                            <input type="checkbox" name="planning" id="planning-applications_check" value="Yes" checked>
                            <label for="planning-applications_check">Planning Applications</label>

                            <ul>
                                <?php 
                                    foreach($planningData as $plan) {
                                        echo '<li><strong>' . 
                                        date("jS F, Y", strtotime(str_replace("T", " ", $plan['casedate']))) . " - " .
                                        $plan['banesstatus'] . '</strong><br /><span>' . 
                                        $plan['locationtext'] . '</span><br /><span><em>' . 
                                        $plan['casetext'] . '</span></em></li>';
                                    }
                                ?>
                            </ul>

                            <?php 
                                $no_planning_apps = array_filter($planningData);
                                    
                                if (empty($no_planning_apps)) {
                                    echo '<p>There haven\'t been any planning applications submitted for your chosen area recently.</p>';
                                }
                            ?>
                        </div>

                        <div id="crimes" class="fourcol">
                            <input type="checkbox" name="crime" id="crimes_check" value="Yes" checked>
                            <label for="crimes_check">Crimes</label>

                            <ul>
                                <?php 
                                    foreach ($crimeData as $crime) {
                                        $crime_nice_name = str_replace("-", " ", $crime['crime_category']);
                                        echo '<li><strong>' . date("F, Y", strtotime(str_replace("T", " ", $crime['month']))) . " " . ' - ' . $crime_nice_name . '</strong><br />' . $crime['street_name'] . '</li>';
                                    }
                                ?>
                            </ul>

                            <?php
                                $no_crimes = array_filter($crimeData);
                                
                                if (empty($no_crimes)) {
                                    echo '<p>There haven\'t been any crimes in your area for a while. That\'s good news!</p>';
                                }
                            ?>
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

                            <?php
                                $no_sales = array_filter($houseData);
                                
                                if (empty($no_sales)) {
                                    echo '<p>There haven\'t been any house sales in your chosen area recently.</p>';
                                }
                            ?>
                        </div>
                    </div>

                    <div id="alert-section">
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