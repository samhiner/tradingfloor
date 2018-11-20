<?php
	session_start();
	include('misc_funcs.php');

	if (!isset($_SESSION['userData'])) {
		header('location: login.php');
	}

?>