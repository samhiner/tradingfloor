<!DOCTYPE html>
<?php

	include('logic/verify.php');

	//insert data into trade log
	function insertData() {
		$inputs = ['stock', 'price', 'amt', 'partner', 'transactType'];

		//make sure every column has data in it, or an error will be triggered later
		for ($x = 0; $x < 5; $x++) {
			if (!isset($_POST[$inputs[$x]])) {
				echo "<script>alert('Not all rows are filled out.');</script>";
				return false;
			} else {
				//remove empty spaces from the arrays of each colum
				//<ul> doesnt keep track of empty spaces so without this <input>s and <ul>s are offset
				$_POST[$inputs[$x]] = array_values(array_diff($_POST[$inputs[$x]], ['']));

				//set the corresponding section of the inputs array to the number filled <input>s in that column
				$inputs[$x] = count($_POST[$inputs[$x]]);
			}
		}

		//TODO this can be broken if one value is missing in every column at different places
		//make sure that there is the same number of answers for each column
		if (count(array_unique($inputs)) == 1) {
			for ($x = 0; $x < $inputs[0]; $x++) {
				if ($_POST['price'][$x] > 0 && $_POST['amt'][$x] > 0) {
					query('INSERT INTO trades(trader, stock, price, amt, partner, transactType) VALUES(?, ?, ?, ?, ?, ?)', 'ssiisi', $_SESSION['userData']['username'], $_POST['stock'][$x], $_POST['price'][$x], $_POST['amt'][$x], $_POST['partner'][$x], $_POST['transactType'][$x]);
				} else {
					echo "<script>alert('If see this, please contact your game administrator. Non-natural number error.');</script>";
				}
			}
			echo "<script>alert('Table successfully submitted!');</script>";
		} else {
			echo "<script>alert('All rows are not filled out.');</script>";
		}
	}

	$status = mysqli_fetch_assoc(query('SELECT * FROM status'));
	$user = mysqli_fetch_assoc(query('SELECT * FROM users WHERE username = ?', 's', $_SESSION['userData']['username']));

	if (isset($_POST['submitStockData'])) {
		//I don't use a var here bc I want to get fresh data on each request
		if ($status['canTrade']) {
			insertData();
		}
	}

?>
<html>
<head>
	<title>Trading Pit Simulator</title>
	<script src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
</head>
<body>
	<?php if ($status['canTrade'] == 1): ?>
		<form method='post'>
			<h2>Trading Simulator Round <?php echo $status['round']; ?></h2>

			<?php
				echo 'You have ' . $user['balance'] . ' dollars.<br>';
				echo 'You have ' . $user['apple'] . ' Apple.<br>';
				echo 'You have ' . $user['nestle'] . ' Nestle.<br>';
				echo 'You have ' . $user['walmart'] . ' Walmart.<br>';

				$showNames = ['Apple', 'Nestle', 'Walmart'];
				$quotas = ['aprice', 'nprice', 'wprice'];
				for ($x = 0; $x < 3; $x++) {
					if ($user['quotatypes'][$x] === '0') {
						echo 'You have to sell one ' . $showNames[$x] . ' stock for $' . $user[$quotas[$x]] . ' or more to break even.<br>';
					} else {
						echo 'You have to buy one ' . $showNames[$x] . ' stock for $' . $user[$quotas[$x]] . ' or less to break even.<br>';
					}
				}
			?>

			<br>Number of Trades: <input type='number' onclick='adjustTradeTable()' value='0' id='numTrades' name='numTrades' onkeydown="return false"><br><br>

			<table border='1' id='tradeTable' style='display: none'>
				<tbody id='trades'>
					<tr>
						<th width='99px'>Stock</th>
						<th width='82px'>Price</th>
						<th width='74px'>Amount</th>
						<th width='154px'>Traded With</th>
						<th width='99px'>Buy/Sell</th>
					</tr>
					<tr id='tradeBox' style='display: none'>
						<td>
							<select name='stock[]'>
								<option disabled selected>Choose One</option>
								<option value='apple'>Apple</option>
								<option value='nestle'>Nestle</option>
								<option value='walmart'>Walmart</option>
							</select>
						</td>
						<td>
							$<input type='number' id='price' name='price[]' style='width: 70px' onkeydown='return (event.keyCode != 190 && event.keyCode != 189);' onkeyup='return numInputVal(event.target)' onclick='return numInputVal(event.target)'>
						</td>
						<td>
							<input type='number' id='amt' name='amt[]' style='width: 70px' onkeydown='return (event.keyCode != 190 && event.keyCode != 189);' onkeyup='return numInputVal(event.target)' onclick='return numInputVal(event.target)'>
						</td>
						<td>
							<input type='text' name='partner[]' style='width: 150px'>
						</td>
						<td>
							<select name='transactType[]'>
								<option disabled selected>Choose One</option>
								<option value='1'>Buy</option>
								<option value='0'>Sell</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table><br>

			<input type='submit' name='submitStockData'>
		</form>
	<?php else: ?>
		Wait for next round.
	<?php endif; ?>

</body>
<script>
	var round = <?php echo $status['round']; ?>;
	var canTrade = <?php echo $status['canTrade']; ?>;

	setInterval(function() {
		$.get('status.php', function(data) {
			[round, canTrade] = data.split(',');
		})
		if (round != <?php echo $status['round']; ?> || canTrade != <?php echo $status['canTrade']; ?>) {
			location.reload();
		}
	}, 1000);
</script>

<script src='index.js'></script>
</html>