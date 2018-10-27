<!DOCTYPE html>
<?php

	include('verify.php');

	if (isset($_POST['stock'])) {
		//TODO insert data into trades table
		$_SESSION['userData']['id']
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

		Number of Trades: <input type='number' onkeydown='adjustTrades()' value='0' id='numTrades'><br><br>

		<table id='trades' border='1'>
			<tr>
				<th>Stock</th>
				<th>Price</th>
				<th>Amount</th>
				<th>Traded With</th>
				<th>Buy/Sell</th>
			</tr>
			<tr id='tradeBox' style='display: none'>
				<td>
					<select name='stock'>
						<option disabled selected>Choose One</option>
						<option value='apple'>Apple</option>
						<option value='nestle'>Nestle</option>
						<option value='walmart'>Walmart</option>
					</select>
				</td>
				<td>
					$<input type='number' name='price'>
				</td>
				<td>
					<input type='number' name='quantity'>
				</td>
				<td>
					<input type='text' name='partner'>
				</td>
				<td>
					<select name='type'>
						<option disabled selected>Choose One</option>
						<option value='buy'>Buy</option>
						<option value='sell'>Sell</option>
					</select>
				</td>
			</tr>
		</table>
	</form>

</body>
<script src='index.js'></script>
</html>
