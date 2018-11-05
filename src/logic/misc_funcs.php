<?php

	function query($prepared, $format, ...$insertVals) {
		global $connect;
		$select = $connect->prepare($prepared);
		$select->bind_param($format, ...$insertVals);
		$select->execute();
		$result = $select->get_result();
		$select->close();
		return $result;
	}

?>