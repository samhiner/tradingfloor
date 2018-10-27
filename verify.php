<?php
	session_start();

	if (!isset($_SESSION['userData'])) {
		header('location: login.php')
	}

	$connect = mysqli_connect('localhost','[USERNAME]', '[PASSWORD]', '[DATABASE]');

	if (mysqli_connect_errno($connect)) {
		echo '<script>alert("Failed to connect to server. Please reload. If this issue persists, alert the system admin");</script>';
	}

	function query($query) {
		global $connect;
		mysqli_query($connect, $query);
	}
?>