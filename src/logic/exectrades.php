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
	if ($trade['transactType'] === 0) {
		$trade = flipTradeSide($trade);
	}

	//update buyer's acct
	query("UPDATE users SET balance = balance - " . $trade["price"] . ", " . $trade["stock"] . " = " . $trade["stock"] . " + " . $trade["amt"] . " WHERE username = '" . $trade["trader"] . "';");
	//update seller's acct
	query("UPDATE users SET balance = balance + " . $trade["price"] . ", " . $trade["stock"] . " = " . $trade["stock"] . " - " . $trade["amt"] . " WHERE username = '" . $trade["partner"] . "';");
}

//TODO write full description
//returns all of the trades that were not fulfilled
function matchTrades() {
	$trades = query('SELECT * FROM trades');
	$register = [];
	while ($row = mysqli_fetch_array($trades, MYSQLI_ASSOC)) {
		$oppTrade = flipTradeSide($row);
		$match = array_search($oppTrade, $register);

		if ($match !== false) {
			array_splice($register, $match, 1);
			execTrade($row);
		} else {
			array_push($register, $row);
		}
	}

	query('DELETE FROM trades');
	return $register;
}

function endRound() {
	query('UPDATE status SET canTrade = 1');
	$leftover = matchTrades();
	query('UPDATE status SET canTrade = 1, round = round + 1');

	return $leftover;
}

function startGame() {
	query('UPDATE status SET canTrade = 1, round = 1');
}

function endGame() {
	query('UPDATE status SET canTrade = 0, round = -1');
}

?>