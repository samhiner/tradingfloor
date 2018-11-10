<!DOCTYPE html>
<html>
<head>
<style>
	body {
		font-family: sans-serif;
	}
</style>
</head>
<body>
	<?php
		session_start();

		//login
		if (isset($_POST['adminPass'])) {
			//this is the hash for "admin", I have a script to change it to something else on the hosted version
			$hashPass = '7dd12f3a9afa0282a575b8ef99dea2a0c1becb51';

			if (hash('ripemd160', $_POST['adminPass']) == $hashPass) {
				$_SESSION['isAdmin'] = true;
			} else {
				echo '<script>alert("Wrong password.");</script>';
			}
		}

		//logout
		if (isset($POST['adminLogout'])) {
			unset($_SESSION['isAdmin']);
		}

		if (isset($_SESSION['isAdmin'])) {
			if (isset($_POST['adminAct'])) {
				if ($_POST['adminAct'] == 'End Round') {
					echo 'gotcha';
					//close trading
					//do calculations and add balances
					//change the next round and re-open trading
				} elseif ($_POST['adminAct'] == 'End Game') {
					echo 'you sure';
				} elseif ($_POST['adminAct'] == 'Logout') {
					unset($_SESSION['isAdmin']);
					echo "<meta http-equiv='refresh' content='0'>";
				}
			}
		}
	?>

	<?php if (isset($_SESSION['isAdmin'])): ?>

		Current Round: <br>
		Number of Trades: <br><br>
		<form method='post'>
			<input type='submit' name='adminAct' value='End Round'><br><br>

			<input type='submit' name='adminAct' value='End Game'><br><br> <!--TODO are you sure-->

			<input type='submit' name='adminAct' value='Logout'>
		</form>

	<?php else: ?>

		<h3>If You Are Not the Admin of This Game, <a href='login.php'>Click Here</a>.</h3>
		Otherwise, log in below:<br><br>
		<form method='post'>
			<input type='password' name='adminPass'><br><br>
			<input type='submit'>
		</form>

	<?php endif; ?>
</body>
</html>