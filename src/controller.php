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
		include('logic/exectrades.php');

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

		if (isset($_POST['adminAct'])) {
			if (isset($_SESSION['isAdmin'])) {
				if ($_POST['adminAct'] === 'End Round') {
					endRound();
					echo "<meta http-equiv='refresh' content='0'>";
				} elseif ($_POST['adminAct'] === 'End Game') {
					endRound();
					endGame();
					echo "<meta http-equiv='refresh' content='0'>";
				} elseif ($_POST['adminAct'] === 'Logout') {
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

			<b>Nontraders:</b><br>
			<ul>
				<?php echo '<li>ok</li>'; ?>
			</ul><br>

			<input type='submit' name='adminAct' value='Logout'><br><br>
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