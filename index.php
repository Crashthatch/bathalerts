<?php include('header.php'); ?>

    <body class="home">
	    <div class="wrap">
	    	<header>
	    		<h1>BathAlerts</h1>
	    		<h2>Get email notifications about things around you</h2>
	    	</header>

	    	<section id="postcode-form">
	    		<form action="report.php" method="get">
	    			<input type="text" name="pc" placeholder="e.g. BA1 5EB">
	    			<button type="submit" class="btn btn-success">
	                	<i class="fa fa-arrow-right"></i>
		            </button>

		            <?php 
		            	$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

						if (strstr( $url, 'unknownPostcode=1' )) {
						   echo '<p class="alert-box fail">Sorry, that\'s not a B&amp;NES postcode that we reocgnise.</p>'; 
						}
					?>
	    		</form>
	    	</section>

	    	<footer></footer>
	    </div>
    </body>
</html>