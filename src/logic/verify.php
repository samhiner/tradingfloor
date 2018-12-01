<?php
	session_start();
	include('misc_funcs.php');

	if (!isset($_SESSION['userData'])) {
		header('location: login.php');
	}

	//makes sure client's forms are not submitted again during jQuery refresh
	//aka this blocks the "resubmit on refresh" feature in Chrome
	echo '<script>
		if (window.history.replaceState) { 
			window.history.replaceState(null, null, window.location.href); 
		}
	</script>';

?>