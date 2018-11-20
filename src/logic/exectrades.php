<?php

//have to go into logic folder again because this is only opened from controller
//and controller is outside of logic
include('logic/misc_funcs.php');

// BUSINESS LOGIC

//get the inverse of the trade submitted (should be the trade that the partner submitted)
function flipTradeSide($trade) {
	//flip trader and partner
	$partner = $trade['partner'];
	$trade['partner'] = $trade['trader'];
	$trade['trader'] = $partner;
	//flip the buy/sell status
	$trade['transactType'] = $trade['transactType'] ? 0 : 1;

	return $trade;
}

function execTrade($trade) {
	//make sure we are looking at the buyers perspective
	if ($trade['transactType'] === 0) {
		$trade = flipTradeSide($trade);
	}

	$buyer = mysqli_fetch_assoc(query('SELECT * FROM users WHERE username = ?', 's', $trade['trader']));
	$seller = mysqli_fetch_assoc(query('SELECT * FROM users WHERE username = ?', 's', $trade['partner']));

	if (($buyer['balance'] - $trade['price'] >= 0) && ($seller[$trade['stock']] - $trade['amt'] >= 0)) {
		//update buyer's acct
		query("UPDATE users SET balance = balance - ?, " . $trade["stock"] . " = " . $trade["stock"] . " + ? WHERE username = ?", 'iis',  $trade["price"], $trade["amt"], $trade["trader"]);
		//update seller's acct
		query("UPDATE users SET balance = balance + ?, " . $trade["stock"] . " = " . $trade["stock"] . " - ? WHERE username = ?", 'iis',  $trade["price"], $trade["amt"], $trade["partner"]);
		return true;
	} else {
		return false;
	}
}

function matchTrades() {
	$trades = query('SELECT * FROM trades');
	$register = [];
	$failedTrades = [];
	while ($row = mysqli_fetch_array($trades, MYSQLI_ASSOC)) {
		$oppTrade = flipTradeSide($row);
		$match = array_search($oppTrade, $register);

		if ($match !== false) {
			array_splice($register, $match, 1);
			$tradeSuccess = execTrade($row);

			if (!$tradeSuccess) {
				$row['transactType'] = 2;
				array_push($failedTrades, $row);
			}
		} else {
			array_push($register, $row);
		}
	}

	query('DELETE FROM trades');

	//add the failed trades back into the database
	foreach (array_merge($register, $failedTrades) as $row) {
		query('INSERT INTO trades(trader, stock, price, amt, partner, transactType) VALUES(?, ?, ?, ?, ?, ?)', 'ssiisi', 
			$row['trader'], 
			$row['stock'], 
			$row['price'],
			$row['amt'],
			$row['partner'],
			$row['transactType']
		);

	}
}

function endRound() {
	query('UPDATE status SET canTrade = 0');
	return matchTrades();
}

function orderAssign() {
	$allUsers = query('SELECT * FROM users'); //TODO this takes everyone who has ever signed up. I need to differentiate acct and profile.
	
	while ($user = mysqli_fetch_assoc($allUsers)) {
		?[0], ?[1], ?[2];//TODO should alternate 1 2 3 then 2 3 1 then 3 1 2
		query('UPDATE users SET aquota = ?, nquota = ?, wquota = ? WHERE username = ?', 'iiis', ?[0], ?[1], ?[2], $user['username']);
	}
}

function startRound() {
	//delete any remaining failed trades
	query('DELETE FROM trades');

	orderAssign();
	
	query('UPDATE status SET canTrade = 1, round = round + 1');
}

function startGame() {
	query('UPDATE status SET canTrade = 1, round = 1');
}

function endGame() {
	query('UPDATE status SET canTrade = 0, round = -1');
}

?>