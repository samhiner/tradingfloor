<?php

	$connect = mysqli_connect('localhost','root', 'root', 'tradingfloor');

	if (mysqli_connect_errno($connect)) {
		echo '<script>alert("Failed to connect to server. Please reload. If this issue persists, alert the system admin");</script>';
	}

	function query($prepared, $format = NULL, ...$insertVals) {
		global $connect;
		if ($format) {
			$select = $connect->prepare($prepared);
			$select->bind_param($format, ...$insertVals);
			$select->execute();
			$result = $select->get_result();
			$select->close();
			return $result;
		} else {
			return mysqli_query($connect, $prepared);
		}
	}

	function cleanInput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		$data = strtolower($data);
		return $data;
	}

?>