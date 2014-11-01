<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">

		<!-- Mobile -->
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />

		<!-- JS -->
		<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>

        <?php if ($pc) { ?>
        	<title>BathAlerts for <?php echo $pc; ?></title>
        <?php }

        else { ?>
        	<title>BathAlerts</title>
        <?php } ?>

        <!-- INSERT Google Analytics -->
    </head>