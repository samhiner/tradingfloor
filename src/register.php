<!DOCTYPE html>
<?php

	// BOILERPLATE

	session_start();

	$connect = mysqli_connect('localhost','root', '', 'tradingfloor');

	if (mysqli_connect_errno($connect)) {
		echo '<script>alert("Failed to connect to server. Please reload. If this issue persists, alert the system admin");</script>';
	}

	function query($query) {
		global $connect;
		return mysqli_query($connect, $query);
	}

	// REGISTERING

	function cleanInput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	//attempt automatic login using session data
	if (isset($_SESSION['userData'])) {
		header('location: index.php');
	}

	//make sure input meets certain criteria defined in comments below
	function backendVal() {
		//if username has correct lengths
		if ((strlen($_POST['username']) >= 1) && (10 >= strlen($_POST['username']))) {
			if ($_POST['password'] == $_POST['passwordConf']) {
				//if password has correct lengths
				if (strlen($_POST['password']) >= 1) {
					return true;
				}
			}
		}
		return false;
	}

	//attempt to register using inputted information
	if (isset($_POST['username'])) {
		$cleanUsername = cleanInput($_POST['username']);
		$cleanPassword = hash('ripemd160', cleanInput($_POST['password']));

		if (backendVal()) {
			$result = query("SELECT * FROM users WHERE username = '$cleanUsername'");

			if (mysqli_num_rows($result) != 1) {
				query("INSERT INTO users(username, password) VALUES('$cleanUsername', '$cleanPassword')");
				$result = query("SELECT * FROM users WHERE username = '$cleanUsername' and password = '$cleanPassword'");

				if (mysqli_num_rows($result) == 1) {
					$_SESSION['userData'] = mysqli_fetch_array($result, MYSQLI_ASSOC);
					header('location: index.php');
				} else {
					echo '<script>alert("Account registration not successful");</script>';
				}
			} else {
				echo '<script>alert("Username already taken.");</script>';
			}
		} else {
			echo '<script>alert("Input validation failed. If you did not manipulate the anything in inspect element, contact your system administrator.");</script>';
		}
	}

?>
<html>
<body>
	<h2>Register</h2>
	<form method='post'>
		Username: <input type='text' name='username' id='username' onkeyup='inputVal()'><br>
		Password: <input type='password' name='password' id='password' onkeyup='inputVal()'><br>
		Confirm Password: <input type='password' name='passwordConf' id='passwordConf' onkeyup='inputVal()'><br><br>
		<input type='submit' id='submit' value='Register' disabled> or <a href='login.php'>Login</a><br><br>
	</form>

	<div id='errors'></div>
</body>
<script src='register.js'></script>
</html>