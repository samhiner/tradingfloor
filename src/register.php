<!DOCTYPE html>
<?php

	// BOILERPLATE

	session_start();

	$connect = mysqli_connect('localhost','[USERNAME]', '[PASSWORD]', '[DATABASE]');

	if (mysqli_connect_errno($connect)) {
		echo '<script>alert("Failed to connect to server. Please reload. If this issue persists, alert the system admin");</script>';
	}

	function query($query) {
		global $connect;
		mysqli_query($connect, $query);
	}

	// REGISTERING

	function cleanInput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	//Attempt automatic login using session data
	if isset($_SESSION['userData']) {
		header('location: index.php');
	}

	//Attempt to register using inputted information
	if (isset($_POST['username'])) {
		$cleanUsername = cleanInput($_POST['username']);
		$cleanPassword = hash('ripemd160', cleanInput($_POST['pass']));

		$result = query("SELECT * FROM users WHERE username = '$cleanUsername'");

		if (mysqli_num_rows($result) != 1) {
			//TODO make sure this works
			//////NOTE if you're having issues it's probably over here
			$_SESSION['userData'] = query("INSERT into users(username, password) VALUES($cleanUsername, $cleanPassword)");
			header('location: index.php');
		} else {
			echo '<script>alert("Username already taken.");</script>';
		}
	}

?>
<html>
<body>
	<h2>Register</h2>
	<form method='post'>
		Username: <input type='text' name='username' onkeydown='inputVal()'><br>
		Password: <input type='password' name='pass' onkeydown='inputVal()'><br>
		Confirm Password: <input type='password' name='passConf' onkeydown='inputVal()'><br><br>
		<input type='submit' value='Register'> or <a href='login.php'>Login</a>
	</form>
</body>
<script src='register.js'></script>
</html>