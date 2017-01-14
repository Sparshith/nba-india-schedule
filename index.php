<?php

include('includes/config.php');

function getAuthUrl() {
    $client = new Google_Client();
    $client->setApplicationName(APPLICATION_NAME);
    $client->setScopes(SCOPES);
    $client->setAuthConfig(CLIENT_SECRET_PATH);
    $client->setRedirectUri(HOST . 'oauth-callback.php');
    $authUrl = $client->createAuthUrl();
    return $authUrl;
}

// Get the API client and construct the service object.
$authUrl = getAuthUrl();

$next_week_date = date('m-d-Y', strtotime('+1 week'));
$subscribed = isset($_SESSION['google_access_token']) &&  $_SESSION['google_access_token']  ? true : false;

unset($_SESSION['google_access_token']);

?>

<!DOCTYPE html>
<html>
    <head>
<!-- jQuery lib -->
<script src="jquery.min.js"></script>
<!-- dateDropper lib -->

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <title> Korv3r </title>
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Dosis:400,700,800,600,300">
        <link rel="icon" href="images/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link href="datedropper.min.css" rel="stylesheet" type="text/css" />
        <style>
			body {
			  	background: #fff;
			  	font-family: "Dosis", Helvetica, Arial, sans-serif;
			  	font-size: 20px;
			  	line-height: 1.7;
			  	margin: 0;
			}

			#header {
			  	text-align: center;
			  	text-transform: uppercase;
			  	background: #f4ee42;
			  	overflow-x: hidden;
			  	width: 100%;
			  	z-index: 9999;
			  	margin-bottom: 10px;
			}
			.submit-button {
			    background-color: #cb202d;
			    color: #fefefe;
			    border-radius: 4px;
			    border: none;
			    padding: 8px 16px;
			    text-align: center;
			    text-decoration: none;
			    display: inline-block;
			    font-size: 16px;
			    cursor: pointer;
			    margin-top: 20px;
			    margin-bottom: 20px;
			    position: relative;
			    text-transform: uppercase;
			    letter-spacing: 1.5px;
			    font-weight: bold;
			}

			.container {
				text-align: center;
			}

			input{
			    border-radius: 4px;
			    font-size: 1.4em;
			    padding: 16px;
			    text-align: center;
			    -webkit-appearance: none;
			    -webkit-box-shadow: 0 0 32px rgba(0,0,0,.1);
			    -moz-box-shadow: 0 0 32px rgba(0,0,0,.1);
			    box-shadow: 0 0 32px rgba(0,0,0,.1);
			    position: relative;
			    z-index: 1;
			    list-style: none;
			    margin: 0;
			    outline: 0;
			    border: 0;
			}

			@media only screen and (max-width: 768px) {
			  input {
			    font-size: 1.1em;
			    padding: 10px;
			  }
			}


			.label {
				margin-bottom: 5px;
			}

			.logo-container {
				display: inline-block;
			    background: #fefefe;
			    border-radius: 50%;
			    padding-left: 3px;
			    padding-right: 2px;
			    padding-bottom: 3px;
			    margin-right: 4px;
			}
		</style>
    </head>
    <body>
        <div id="header">Korv3r</div>
        <h2 style="text-align: center;">
        	<?php echo $subscribed ? 'Successfully added matches from  '. $_COOKIE['fromDate'] .' to  '.$_COOKIE['toDate'].' to your calendar.' : 'Add the NBA India schedule to your google calendar!'; ?></h2>
        <div class="container">
        	<?php if(!$subscribed): ?>
        	<div style="margin-bottom: 30px;margin-top: 30px">
        		<div class="label">When do you want the events to start showing up?</div>
	        	<input type="text" data-format="F S, Y" id="from-date" data-large-mode="true" placeholder="Choose From Date" data-lock="from"/>
	        </div>
	        <div style="margin-bottom: 30px;"">
	        	<div class="label">When do you want it to stop?</div>
	        	<input type="text" data-format="F S, Y" id="to-date" data-large-mode="true" data-default-date="<?php echo $next_week_date; ?>" placeholder="Choose To Date"/>
	        </div>
	        <a href="<?php echo $authUrl; ?>" id="submit">
	        	<div class="submit-button">
	        		<div class="logo-container">
	        			<img src="images/google.png" height="25px" style="vertical-align: middle;">
	        		</div>
	        		<span style="vertical-align: middle;"> Add to calendar </span>
	        	</div>
			</a>
			<?php else: ?>
				<div>
					<img src="images/korvershot.gif"/>
				</div>
			<?php endif; ?>
		</div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="datedropper.min.js"></script>
  </body>
</html>

<script>
	$('input').dateDropper();
	$('#submit').on('click', function(event){
		event.preventDefault();
		var fromDate = $('#from-date').val();
		var toDate = $('#to-date').val();
		setCookie('fromDate', fromDate);
		setCookie('toDate', toDate);
		window.location.href = $(this).attr('href');
	});

	function setCookie(name, value, numberOfDays)  {
    	numberOfDays = numberOfDays || (365*5);
    	var today = new Date();
    	var expire = new Date();
    	expire.setTime(today.getTime() + 3600000*24*numberOfDays);
    	document.cookie = name+"="+escape(value) + ";path=/;expires="+expire.toGMTString();
	}


</script>





