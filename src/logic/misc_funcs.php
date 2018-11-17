<?php

	//TODO could automatically do format
	function query($prepared, $format, ...$insertVals) {
		global $connect;
		$select = $connect->prepare($prepared);
		$select->bind_param($format, ...$insertVals);
		$select->execute();
		$result = $select->get_result();
		$select->close();
		return $result;
	}

	function cleanInput($data) { //TODO make sure this actually prevents injection
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		$data = strtolower($data);
		return $data;
	}

?>