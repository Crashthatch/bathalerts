<?php include('header.php'); ?>

    <body class="home">
	    <div class="wrap">
	    	<header>
	    		<h1>BathAlerts</h1>
	    		<h2>Get email notifications about things around you</h2>
	    	</header>

	    	<section id="postcode-form">
                <script type="text/javascript">
                function redirect() {
                    window.location = "/report/" + $('#postcode').val().replace(/\s/g, '');
                    return false;
                }
                </script>
	    		<form action="/report/" method="get" onsubmit="return redirect()">
	    			<input type="text" name="pc" placeholder="e.g. BA1 5EB" id="postcode" />
	    			<button type="submit" class="btn btn-success">
	                	<i class="fa fa-arrow-right"></i>
		            </button>

		            <?php 
		            	$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

						if (strstr( $url, 'unknownPostcode=1' )) {
						   echo '<p class="alert-box">Sorry, that\'s not a B&amp;NES postcode that we recognise.</p>'; 
						}
					?>
	    		</form>
	    	</section>
	    </div>

		<?php 
		$current_page = basename(__FILE__, '.php');
		include('footer.php'); ?>