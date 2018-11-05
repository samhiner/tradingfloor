<?php
	session_start();
	include('misc_funcs.php');

	if (!isset($_SESSION['userData'])) {
		header('location: login.php');
	}

	$connect = mysqli_connect('localhost','root', '', 'tradingfloor');

	if (mysqli_connect_errno($connect)) {
		echo '<script>alert("Failed to connect to server. Please reload. If this issue persists, alert the system admin");</script>';
	}

?>