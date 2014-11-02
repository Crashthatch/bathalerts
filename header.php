<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
		<meta name="viewport" content="width=1280" />

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/library/css/style.css">
		<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css">
		<link rel="stylesheet" href="/bower_components/leaflet.awesome-markers/dist/leaflet.awesome-markers.css">
		<link rel="stylesheet" href="/bower_components/leaflet.draw/dist/leaflet.draw.css">
		<link rel="stylesheet" href="/library/css/map.css">
		<link rel="stylesheet" href="/bower_components/leaflet.markerClusterer/dist/MarkerCluster.css">
		<link rel="stylesheet" href="/bower_components/leaflet.markerClusterer/dist/MarkerCluster.Default.css">

		<!-- JS -->
		<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
        <script src="/bower_components/leaflet.awesome-markers/dist/leaflet.awesome-markers.min.js"></script>
        <script src="/bower_components/leaflet.draw/dist/leaflet.draw.js"></script>
        <script src="/bower_components/leaflet.draw/src/edit/handler/Edit.Circle.js"></script>
        <script src="/bower_components/leaflet.markerclusterer/dist/leaflet.markercluster.js"></script>
        <script src="/library/js/scripts.min.js"></script>

        <?php if (isset($pc)) { ?>
        	<title>BathAlerts for <?php echo $pc->toString(); ?></title>
        <?php }

        else { ?>
        	<title>BathAlerts</title>
        <?php } ?>

        <!-- INSERT Google Analytics -->
    </head>