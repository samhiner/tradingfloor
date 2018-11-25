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
				} elseif ($_POST['adminAct'] === 'End Game') {
					endGame();
				} elseif ($_POST['adminAct'] === 'Logout') {
					unset($_SESSION['isAdmin']);
				} elseif ($_POST['adminAct'] === 'Start Game') {
					startGame();
				} elseif ($_POST['adminAct'] === 'Start Round') {
					startRound();					
				} elseif ($_POST['adminAct'] === 'Match Trades') {
					query($_POST['adminSQL']);
					matchTrades();
				}
				echo "<meta http-equiv='refresh' content='0'>";
			}
		}

		$status = mysqli_fetch_assoc(query('SELECT * FROM status'));
		$trades = query('SELECT * FROM trades');
	?>

	<?php if (isset($_SESSION['isAdmin'])): ?>

		<?php if (($status['round'] != -1) && ($status['round'] != 0)): ?>
			Current Round: <?php echo $status['round']; ?><br>
			Trading? <?php echo $status['canTrade'] ?><br>
			Number of Trades: <?php echo mysqli_num_rows($trades); ?><br><br>

			<form method='post'>
				<?php if ($status['canTrade'] == 1): ?>
					<input type='submit' name='adminAct' value='End Round'><br><br>
				<?php else: ?>
					<input type='submit' name='adminAct' value='Start Round'><br><br>
					<input type='submit' name='adminAct' value='End Game'><br><br>
				<?php endif; ?>

				<?php if ($status['canTrade'] != 1): ?>
					<b>Failed Trades</b>
					<ul>
					<?php
						while ($row = mysqli_fetch_assoc($trades)) {
							echo '<li>' . $row['trader'] . ' ' . $row['partner'] . ' $' .  $row['price'] . ' ' . $row['amt'] . ' ' . $row['transactType'] . ' ' . $row['stock'] . '</li>';
						}
					?>
					</ul><br>

					<textarea name='adminSQL' rows="4" cols="50">
						This is a terrible idea. Fix if used more than once.
					</textarea><br>

					<input type='submit' name='adminAct' value='Match Trades'><br><br><br>
				<?php endif; ?>

				<input type='submit' name='adminAct' value='Logout'><br><br>
			</form>
		<?php elseif ($status['round'] == 0): ?>
			<form method='post'>
				<input type='submit' name='adminAct' value='Start Game'><br><br>
			</form>
		<?php else: ?>
			Game Over
		<?php endif; ?>

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