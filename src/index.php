<!DOCTYPE html>
<?php

	include('logic/verify.php');

	//insert data into trade log
	if (isset($_POST['stock'])) {
		$inputSizes = [count($_POST['stock']), count($POST['price']), count($POST['quantity']), count($POST['partner']), count($POST['transactType'])];
		#TODO this can be broken if one value is missing in every column at different places
		if (count(array_unique($inputSizes)) == 1) {
			for ($x = 0; $x < $_POST['numTrades']; $x++) {
				#TODO if price is a full num and positive and any other necessary input validation. that should be all but idk
				#if ($_POST['price'][$x] > 0 || $_POST[''] )
				#query('INSERT INTO trades(stock, price, quantity, partner, transactType) VALUES(?, ?, ?, ?, ?)', 'siiss', $_POST['stock'][$x], $POST['price'][$x], $POST['quantity'][$x], $POST['partner'][$x], $POST['transactType'][$x]);
			}
		} else {
			echo "<script>alert('All rows are not filled out.');</script>";
		}
	}

?>
<html>
<head>
	<title>Trading Pit Simulator</title>
</head>
<!--NOTE: will have to do a setup where you put in your name and password to make sure there are no duplicates and so I have password on record
also do sql injection attack protection etc; this system should be robust and handle stuff like negative numbers-->
<body>
	<form method='post'>
		<h2>Trading Simulator Round INPUT</h2>

		Number of Trades: <input type='number' onkeyup='adjustTrades()' onclick='adjustTrades()' value='0' id='numTrades' name='numTrades' onkeydown="return false"><br><br>

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
						$<input type='number' name='price[]' style='width: 70px' onkeydown='return (event.keyCode != 190);'>
					</td>
					<td>
						<input type='number' name='quantity[]' style='width: 70px' onkeydown='return (event.keyCode != 190);'>
					</td>
					<td>
						<!--TODO make this a list so people don't mess up-->
						<input type='text' name='partner[]' style='width: 150px'>
					</td>
					<td>
						<select name='transactType[]'>
							<option disabled selected>Choose One</option>
							<option value='buy'>Buy</option>
							<option value='sell'>Sell</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>

		<input type='submit'>
	</form>

</body>
<script src='index.js'></script>
</html>
