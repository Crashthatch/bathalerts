<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
		<meta name="viewport" content="width=1280" />

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/library/css/style.css">
		<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css">
		<link rel="stylesheet" href="/bower_components/leaflet-awesome-markers/dist/leaflet.awesome-markers.css">
		<link rel="stylesheet" href="/bower_components/leaflet-draw/dist/leaflet.draw.css">
		<link rel="stylesheet" href="/library/css/map.css">

		<!-- JS -->
		<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
        <script src="/bower_components/leaflet-awesome-markers/dist/leaflet.awesome-markers.min.js"></script>
        <script src="/bower_components/leaflet-draw/dist/leaflet.draw.js"></script>
        <script src="/bower_components/leaflet-draw/src/edit/handler/Edit.Circle.js"></script>
        <script src="/bower_components/leaflet-markerclusterer/dist/leaflet.markercluster.js"></script>
        <script src="/library/js/scripts.min.js"></script>

        <?php if (isset($pc)) { ?>
        	<title>BathAlerts for <?php echo $pc->toString(); ?></title>
        <?php }

        else { ?>
        	<title>BathAlerts</title>
        <?php } ?>

        <!-- Google Anayltics -->
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-18121708-8', 'auto');
            ga('send', 'pageview');
        </script>
    </head>