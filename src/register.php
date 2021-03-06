<!DOCTYPE html>
<?php

	// BOILERPLATE

	session_start();
	include('logic/misc_funcs.php');

	// REGISTERING

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
			$result = query('SELECT * FROM users WHERE username = ?', 's', $cleanUsername);

			if (mysqli_num_rows($result) != 1) {
				query('INSERT INTO users(username, password) VALUES(?, ?)', 'ss', $cleanUsername, $cleanPassword);
				$result = query('SELECT * FROM users WHERE username = ? and password = ?', 'ss', $cleanUsername, $cleanPassword);

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
		Username: <input type='text' name='username' id='username' onkeydown='inputVal()'><br>
		Password: <input type='password' name='password' id='password' onkeydown='inputVal()'><br>
		Confirm Password: <input type='password' name='passwordConf' id='passwordConf' onkeyup='inputVal()'><br><br>
		<input type='submit' id='submit' value='Register' disabled> or <a href='login.php'>Login</a><br><br>
	</form>

	<div id='errors'></div>
</body>
<script src='register.js'></script>
</html>