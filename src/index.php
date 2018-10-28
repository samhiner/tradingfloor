<!DOCTYPE html>
<?php

	include('logic/verify.php');

	//insert data into trade log
	if (isset($_POST['stock'])) {
		//TODO insert data into trades table
		//$_SESSION['userData']['id']
		echo 'works';
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

		Number of Trades: <input type='number' onkeyup='adjustTrades()' onclick='adjustTrades()' value='0' id='numTrades' onkeydown="return false"><br><br>

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
						<select name='stock'>
							<option disabled selected>Choose One</option>
							<option value='apple'>Apple</option>
							<option value='nestle'>Nestle</option>
							<option value='walmart'>Walmart</option>
						</select>
					</td>
					<td>
						$<input type='number' name='price' style='width: 70px'>
					</td>
					<td>
						<input type='number' name='quantity' style='width: 70px'>
					</td>
					<td>
						<input type='text' name='partner' style='width: 150px'>
					</td>
					<td>
						<select name='type'>
							<option disabled selected>Choose One</option>
							<option value='buy'>Buy</option>
							<option value='sell'>Sell</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
	</form>

</body>
<script src='index.js'></script>
</html>
