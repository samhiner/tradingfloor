<!DOCTYPE html>
<?php

	// BOILERPLATE

	session_start();
	include('logic/misc_funcs.php');

	$connect = mysqli_connect('localhost','root', '', 'tradingfloor');

	if (mysqli_connect_errno($connect)) {
		echo '<script>alert("Failed to connect to server. Please reload. If this issue persists, alert the system admin");</script>';
	}

	// LOGIN HANDLING

	function cleanInput($data) { //TODO make sure this actually prevents injection
		$data = trim($data);		//TODO make it so you dont have to include file endings in urls
		$data = stripslashes($data);//TODO fix headers and write that really cool gui application in notes
		$data = htmlspecialchars($data);
		return $data;
	}

	//Attempt automatic login using session data
	if (isset($_SESSION['userData'])) {
		header('location: index.php');
	}

	//Attempt login using inputted credentials
	if (isset($_POST['username'])) {
		$cleanUsername = cleanInput($_POST['username']);
		$cleanPassword = hash('ripemd160', cleanInput($_POST['password']));

		$result = query('SELECT * FROM users WHERE username = ? and password = ?', 'ss', $cleanUsername, $cleanPassword);

		if (mysqli_num_rows($result) == 1) {
			$_SESSION['userData'] = mysqli_fetch_array($result, MYSQLI_ASSOC);
			header('location: index.php');
		} else {
			echo '<script>alert("Username and/or password incorrect");</script>';
		}
	}
?>
<html>
<body>
	<h2>Login</h2>
	<form method='post'>
		Username: <input type='text' name='username'><br>
		Password: <input type='password' name='password'><br><br>
		<input type='submit' id='submit' value='Login'> or <a href='register.php'>Register</a>
	</form>
</body>
</html>